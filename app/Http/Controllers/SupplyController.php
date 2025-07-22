<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\DisbursementItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplyController extends Controller
{
    public function index()
    {
        $supplies = Supply::with(['supplier', 'items.product'])->latest()->get();
        return view('supplies.index', compact('supplies'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('supplies.create', compact('suppliers', 'products'));
    }

    /**
     * دالة لإعادة حساب كمية المنتج بعد كل عملية
     */
    protected function recalculateProductQuantity($productId)
    {
        $totalSupplied = DB::table('supply_items')->where('product_id', $productId)->sum('quantity');
        $totalDisbursed = DB::table('disbursement_items')->where('product_id', $productId)->sum('quantity');
        $product = Product::find($productId);
        if ($product) {
            $product->supply_quantity = $totalSupplied - $totalDisbursed;
            $product->save();
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'supply_date' => 'required|date',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|numeric|min:0.01'
            ]);
            
            DB::beginTransaction();
            
            // إنشاء التوريد
            $supply = Supply::create([
                'supplier_id' => $validated['supplier_id'],
                'supply_date' => $validated['supply_date']
            ]);
            
            // إضافة المنتجات
            foreach ($validated['products'] as $product) {
                $supply->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity']
                ]);
                // إعادة حساب كمية المنتج
                $this->recalculateProductQuantity($product['product_id']);
            }
            
            DB::commit();
            
            return redirect()
                ->route('supplies.index')
                ->with('success', 'تم إضافة التوريد بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
           
            
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة التوريد: ' . $e->getMessage());
        }
    }

    public function edit(Supply $supply)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $supplyItems = $supply->items()->with('product')->get();
        
        return view('supplies.edit', compact('supply', 'suppliers', 'products', 'supplyItems'));
    }

    public function update(Request $request, Supply $supply)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'supply_date' => 'required|date',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|numeric|min:0.01'
            ]);

            DB::beginTransaction();

            // جلب المنتجات القديمة
            $oldItems = $supply->items()->get()->keyBy('product_id');
            $newItems = collect($validated['products'])->keyBy('product_id');

            // تحقق من الكميات المصروفة لكل منتج
            foreach ($newItems as $productId => $product) {
                $disbursedQuantity = DB::table('disbursement_items')
                    ->where('product_id', $productId)
                    ->sum('quantity');
                if ($disbursedQuantity > $product['quantity']) {
                    throw new \Exception('لا يمكن تعديل الكمية لأنها أقل من الكمية المصروفة (' . $disbursedQuantity . ')');
                }
            }

            // تحديث بيانات التوريد
            $supply->update([
                'supplier_id' => $validated['supplier_id'],
                'supply_date' => $validated['supply_date']
            ]);

            // تحديث أو إضافة أو حذف عناصر التوريد
            // حذف المنتجات التي لم تعد موجودة
            foreach ($oldItems as $productId => $item) {
                if (!$newItems->has($productId)) {
                    $item->delete();
                    $this->recalculateProductQuantity($productId);
                }
            }
            // تحديث أو إضافة المنتجات الجديدة
            foreach ($newItems as $productId => $product) {
                $item = $oldItems->get($productId);
                if ($item) {
                    // تحديث الكمية إذا تغيرت
                    if ($item->quantity != $product['quantity']) {
                        $item->quantity = $product['quantity'];
                        $item->save();
                    }
                } else {
                    // إضافة منتج جديد
                    $supply->items()->create([
                        'product_id' => $productId,
                        'quantity' => $product['quantity']
                    ]);
                }
                $this->recalculateProductQuantity($productId);
            }

            DB::commit();

            return redirect()
                ->route('supplies.index')
                ->with('success', 'تم تحديث التوريد بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * حذف التوريد
     */
    public function destroy(Supply $supply)
    {
        try {
            DB::beginTransaction();
            // تحقق من إمكانية حذف التوريد
            foreach ($supply->items as $item) {
                $productId = $item->product_id;
                $totalSuppliedAfterDelete = DB::table('supply_items')
                    ->where('product_id', $productId)
                    ->where('supply_id', '!=', $supply->id)
                    ->sum('quantity');
                $totalDisbursed = DB::table('disbursement_items')
                    ->where('product_id', $productId)
                    ->sum('quantity');
                if ($totalSuppliedAfterDelete < $totalDisbursed) {
                    $product = Product::find($productId);
                    throw new \Exception("لا يمكن حذف هذا التوريد لأن الكمية المتبقية غير كافية للمنتج: {$product->name}");
                }
            }
            // حذف عناصر التوريد
            foreach ($supply->items as $item) {
                $productId = $item->product_id;
                $item->delete();
                $this->recalculateProductQuantity($productId);
            }
            // حذف التوريد
            $supply->delete();
            DB::commit();
            return redirect()
                ->route('supplies.index')
                ->with('success', 'تم حذف التوريد بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'حدث خطأ أثناء حذف التوريد: ' . $e->getMessage());
        }
    }

    /**
     * طباعة مستند التوريد
     */
    public function print(Supply $supply)
    {
        // تحميل العلاقات المطلوبة
        $supply->load(['items.product.company', 'items.product.unitType', 'supplier']);

        $sales = $supply->items->map(function ($item) {
            return [
                'product_id' => $item->product->id ?? '',
                'product_name' => $item->product->name ?? '',
                'company_name' => $item->product->company->name ?? '',
                'unit_name' => $item->product->unitType->name ?? '',
                'qty' => $item->quantity
            ];
        });

        return view('supplies.print', [
            'date' => $supply->supply_date->format('Y-m-d'),
            'supplier_name' => $supply->supplier->name ?? '',
            'invoice_number' => $supply->invoice_number ?? '',
            'sales' => $sales
        ]);
    }
} 