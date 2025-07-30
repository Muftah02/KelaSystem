<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user', 'items.product']);

        // البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // فلترة حسب الحالة
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب حالة الدفع
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // فلترة حسب العميل
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,transfer,check',
            'payment_status' => 'required|in:pending,paid,partial',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'shipping_address' => 'nullable|array',
            'billing_address' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // التحقق من توفر المنتجات
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product->canSell($item['quantity'])) {
                    throw new \Exception("Product {$product->name} is not available in requested quantity");
                }
            }

            // إنشاء الطلب
            $orderData = $request->except(['items']);
            $orderData['user_id'] = auth()->id();
            $orderData['subtotal'] = 0;
            $orderData['tax_amount'] = 0;
            $orderData['total_amount'] = 0;
            $orderData['discount_amount'] = $orderData['discount_amount'] ?? 0;
            $orderData['shipping_cost'] = $orderData['shipping_cost'] ?? 0;

            $order = Order::create($orderData);

            // إضافة عناصر الطلب
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $unitPrice = $item['unit_price'] ?? $product->current_price;
                
                $order->addItem($product, $item['quantity'], $unitPrice);
                
                // تحديث المخزون
                $product->updateStock($item['quantity'], 'decrease');
            }

            // حساب الإجماليات
            $order->calculateTotals();

            // تحديث معلومات العميل
            if ($order->customer) {
                $order->customer->updateTotals();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order->load(['customer', 'items.product'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'user', 'items.product'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled,refunded,on_hold',
            'payment_status' => 'sometimes|in:pending,paid,partial,refunded',
            'payment_method' => 'sometimes|in:cash,card,transfer,check',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'shipping_address' => 'nullable|array',
            'billing_address' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->all());

        // إعادة حساب الإجماليات إذا تغيرت الخصومات أو الشحن
        if ($request->has(['discount_percentage', 'discount_amount', 'shipping_cost'])) {
            $order->calculateTotals();
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order->load(['customer', 'items.product'])
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // لا يمكن حذف الطلبات المكتملة
        if (in_array($order->status, ['delivered', 'shipped'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete completed orders'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // إعادة المنتجات للمخزون
            foreach ($order->items as $item) {
                $item->product->updateStock($item->quantity, 'increase');
            }

            $order->delete();

            // تحديث معلومات العميل
            if ($order->customer) {
                $order->customer->updateTotals();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: ' . $e->getMessage()
            ], 400);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded,on_hold',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->updateStatus($request->status);

        if ($request->notes) {
            $order->update(['internal_notes' => $request->notes]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order->fresh()
        ]);
    }

    public function todayOrders()
    {
        $orders = Order::today()
                      ->with(['customer', 'items'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'total_amount' => $orders->sum('total_amount'),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'completed_orders' => $orders->where('status', 'delivered')->count()
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'orders' => $orders,
                'stats' => $stats
            ]
        ]);
    }

    public function receipt($id)
    {
        $order = Order::with(['customer', 'items.product'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // هنا يمكن إضافة منطق إنشاء الفاتورة/الإيصال
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}