<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Models\Category;
use App\Models\UnitType;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['company', 'category', 'unitType', 'supplies'])->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $companies = Company::all();
        $categories = Category::all();
        $unitTypes = UnitType::all();
        return view('products.create', compact('companies', 'categories', 'unitTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'unit_type_id' => 'required|exists:unit_types,id',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $companies = Company::all();
        $categories = Category::all();
        $unitTypes = UnitType::all();
        return view('products.edit', compact('product', 'companies', 'categories', 'unitTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'unit_type_id' => 'required|exists:unit_types,id',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        try {
            // التحقق من وجود علاقات في فواتير الصرف
            if ($product->disbursementItems()->exists()) {
                $count = $product->disbursementItems()->count();
                return redirect()->route('products.index')
                    ->with('error', "لا يمكن حذف المنتج لارتباطه بـ {$count} فاتورة صرف");
            }

            // التحقق من وجود علاقات في فواتير التوريد
            if ($product->supplyItems()->exists()) {
                $count = $product->supplyItems()->count();
                return redirect()->route('products.index')
                    ->with('error', "لا يمكن حذف المنتج لارتباطه بـ {$count} فاتورة توريد");
            }

            $product->delete();
            return redirect()->route('products.index')
                ->with('success', 'تم حذف المنتج بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage());
        }
    }

    public function searchCompanies(Request $request)
    {
        $search = $request->get('search');
        $companies = Company::where('name', 'like', "%{$search}%")->get();
        return response()->json($companies);
    }

    public function searchCategories(Request $request)
    {
        $search = $request->get('search');
        $categories = Category::where('name', 'like', "%{$search}%")->get();
        return response()->json($categories);
    }
} 