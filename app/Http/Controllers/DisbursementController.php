<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Product;
use App\Models\Customer;
use App\Models\DisbursementItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DisbursementController extends Controller
{
    /**
     * عرض قائمة الصرف
     */
    public function index()
    {
        $disbursements = Disbursement::with(['customer', 'items.product'])
            ->latest()
            ->get();
            
        return view('disbursements.index', compact('disbursements'));
    }

    /**
     * عرض نموذج إنشاء صرف جديد
     */
    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        
        return view('disbursements.create', compact('products', 'customers'));
    }

    /**
     * حفظ الصرف الجديد
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'disbursement_date' => 'required|date',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|numeric|min:0.01'
            ]);
            
            DB::beginTransaction();
            
            // التحقق من توفر الكميات
            foreach ($validated['products'] as $product) {
                $productModel = Product::findOrFail($product['product_id']);
                if ($productModel->supply_quantity < $product['quantity']) {
                    throw new \Exception("الكمية المطلوبة غير متوفرة للمنتج: {$productModel->name}");
                }
            }
            
            // إنشاء الصرف
            $disbursement = Disbursement::create([
                'customer_id' => $validated['customer_id'],
                'disbursement_date' => $validated['disbursement_date']
            ]);
            
            // إضافة المنتجات وتحديث الكميات
            foreach ($validated['products'] as $product) {
                $disbursementItem = $disbursement->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity']
                ]);

                // تحديث الكمية المتوفرة للمنتج
                $productModel = Product::find($product['product_id']);
                $productModel->supply_quantity -= $product['quantity'];
                $productModel->save();
            }
            
            DB::commit();
            
            return redirect()
                ->route('disbursements.index')
                ->with('success', 'تم إضافة الصرف بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in disbursement store:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الصرف: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل الصرف
     */
    public function edit(Disbursement $disbursement)
    {
        $products = Product::all();
        $customers = Customer::all();
        $disbursement->load('items');
        
        return view('disbursements.edit', compact('disbursement', 'products', 'customers'));
    }

    /**
     * تحديث بيانات الصرف
     */
    public function update(Request $request, Disbursement $disbursement)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'disbursement_date' => 'required|date',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|numeric|min:0.01'
            ]);
            
            DB::beginTransaction();
            
            // إعادة الكميات القديمة
            foreach ($disbursement->items as $item) {
                $product = Product::find($item->product_id);
                $product->supply_quantity += $item->quantity;
                $product->save();
            }
            
            // التحقق من توفر الكميات الجديدة
            foreach ($validated['products'] as $product) {
                $productModel = Product::findOrFail($product['product_id']);
                if ($productModel->supply_quantity < $product['quantity']) {
                    throw new \Exception("الكمية المطلوبة غير متوفرة للمنتج: {$productModel->name}");
                }
            }
            
            // تحديث بيانات الصرف
            $disbursement->update([
                'customer_id' => $validated['customer_id'],
                'disbursement_date' => $validated['disbursement_date']
            ]);
            
            // حذف المنتجات القديمة
            $disbursement->items()->delete();
            
            // إضافة المنتجات الجديدة وتحديث الكميات
            foreach ($validated['products'] as $product) {
                $disbursementItem = $disbursement->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity']
                ]);

                // تحديث الكمية المتوفرة للمنتج
                $productModel = Product::find($product['product_id']);
                $productModel->supply_quantity -= $product['quantity'];
                $productModel->save();
            }
            
            DB::commit();
            
            return redirect()
                ->route('disbursements.index')
                ->with('success', 'تم تحديث الصرف بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in disbursement update:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'حدث خطأ أثناء تحديث الصرف: ' . $e->getMessage());
        }
    }

    /**
     * حذف الصرف
     */
    public function destroy(Disbursement $disbursement)
    {
        try {
            DB::beginTransaction();
            
            // التحقق من الكميات قبل الحذف
            foreach ($disbursement->items as $item) {
                $product = Product::find($item->product_id);
                $totalDisbursed = DisbursementItem::where('product_id', $product->id)
                    ->where('disbursement_id', '!=', $disbursement->id)
                    ->sum('quantity');
                
                // إذا كانت الكمية المتبقية أقل من الكمية المصروفة، نمنع الحذف
                if (($product->supply_quantity + $item->quantity) < $totalDisbursed) {
                    throw new \Exception("لا يمكن حذف هذا الصرف لأن الكمية المتبقية غير كافية للمنتج: {$product->name}");
                }
            }
            
            // إعادة الكميات
            foreach ($disbursement->items as $item) {
                $product = Product::find($item->product_id);
                $product->supply_quantity += $item->quantity;
                $product->save();
            }
            
            // حذف الصرف وعناصره
            $disbursement->items()->delete();
            $disbursement->delete();
            
            DB::commit();
            
            return redirect()
                ->route('disbursements.index')
                ->with('success', 'تم حذف الصرف بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in disbursement destroy:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'حدث خطأ أثناء حذف الصرف: ' . $e->getMessage());
        }
    }

    /**
     * طباعة مستند الصرف
     */
    public function print(Disbursement $disbursement)
    {
        // تحميل العلاقات المطلوبة
        $disbursement->load(['items.product.category', 'items.product.unitType', 'customer']);

        $sales = $disbursement->items->map(function ($item) {
            return [
                'product_id' => $item->product->id ?? '',
                'product_name' => $item->product->name ?? '',
                'category_name' => $item->product->category->name ?? '',
                'unit_name' => $item->product->unitType->name ?? '',
                'qty' => $item->quantity
            ];
        });

        return view('disbursements.print', [
            'date' => $disbursement->disbursement_date->format('Y-m-d'),
            'customer_name' => $disbursement->customer->name ?? '',
            'invoice_number' => $disbursement->id,
            'sales' => $sales
        ]);
    }
} 