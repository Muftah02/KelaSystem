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

            // التحقق من الكميات المصروفة قبل التحديث
            foreach ($validated['products'] as $product) {
                try {
                    $disbursedQuantity = DB::table('disbursement_items')
                        ->where('product_id', $product['product_id'])
                        ->sum('quantity');

                    if ($disbursedQuantity > $product['quantity']) {
                        throw new \Exception('لا يمكن تعديل الكمية لأنها أقل من الكمية المصروفة (' . $disbursedQuantity . ')');
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // في حالة عدم وجود عمود supply_id، نتحقق من الصرفيات بطريقة أخرى
                    $disbursedQuantity = DB::table('disbursement_items')
                        ->where('product_id', $product['product_id'])
                        ->sum('quantity');

                    if ($disbursedQuantity > $product['quantity']) {
                        throw new \Exception('لا يمكن تعديل الكمية لأنها أقل من الكمية المصروفة (' . $disbursedQuantity . ')');
                    }
                }
            }
            
            // تحديث بيانات التوريد
            $supply->update([
                'supplier_id' => $validated['supplier_id'],
                'supply_date' => $validated['supply_date']
            ]);

            
            // حذف عناصر التوريد القديمة
            $supply->items()->delete();
            
            // إضافة عناصر التوريد الجديدة وتحديث الكميات
            foreach ($validated['products'] as $product) {
                // إضافة عنصر التوريد الجديد
                $supply->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity']
                ]);

                // تحديث كمية المنتج
                $productModel = Product::find($product['product_id']);
                if ($productModel) {
                    // حساب الكمية المتبقية = الكمية الجديدة - الكمية المصروفة
                    $disbursedQuantity = DB::table('disbursement_items')
                        ->where('product_id', $product['product_id'])
                        ->sum('quantity');
                    
                    $productModel->supply_quantity = $product['quantity'] - $disbursedQuantity;
                    $productModel->save();
                    
                  
                }
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
            
            // التحقق من الكميات قبل الحذف
            foreach ($supply->items as $item) {
                $product = Product::find($item->product_id);
                $totalDisbursed = DisbursementItem::where('product_id', $product->id)->sum('quantity');
                
                // إذا كانت الكمية المتبقية أقل من الكمية المصروفة، نمنع الحذف
                if (($product->supply_quantity - $item->quantity) < $totalDisbursed) {
                    throw new \Exception("لا يمكن حذف هذا التوريد لأن الكمية المتبقية غير كافية للمنتج: {$product->name}");
                }
            }
            
            // إعادة الكميات
            foreach ($supply->items as $item) {
                $product = Product::find($item->product_id);
                $product->supply_quantity -= $item->quantity;
                $product->save();
            }
            
            // حذف التوريد وعناصره
            $supply->items()->delete();
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
        $supply->load(['items.product.category', 'items.product.unitType', 'supplier']);

        $sales = $supply->items->map(function ($item) {
            return [
                'product_id' => $item->product->id ?? '',
                'product_name' => $item->product->name ?? '',
                'category_name' => $item->product->category->name ?? '',
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