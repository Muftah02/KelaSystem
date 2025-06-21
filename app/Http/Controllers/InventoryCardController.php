<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class InventoryCardController extends Controller
{
    /**
     * عرض بطاقة جرد المنتجات
     */
    public function index(Request $request)
    {
        // جلب التصنيفات والشركات للفلاتر
        $categories = Category::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        // بناء الاستعلام
        $query = Product::with(['category', 'company'])
            ->select('products.*')
            ->when($request->quantity_filter, function ($query, $filter) {
                if ($filter === 'positive') {
                    return $query->where('supply_quantity', '>', 0);
                } elseif ($filter === 'zero') {
                    return $query->where('supply_quantity', '<=', 0);
                }
            })
            ->when($request->category_id, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->company_id, function ($query, $companyId) {
                return $query->where('company_id', $companyId);
            });

        // جلب المنتجات مع الترتيب
        $products = $query->orderBy('name')->get();

        return view('inventory-card.index', compact('products', 'categories', 'companies'));
    }

    /**
     * طباعة بطاقة الجرد
     */
    public function print(Request $request)
    {
        // جلب المنتجات مع تطبيق نفس الفلاتر
        $query = Product::with(['category', 'company'])
            ->when($request->quantity_filter, function ($query, $filter) {
                if ($filter === 'positive') {
                    return $query->where('supply_quantity', '>', 0);
                } elseif ($filter === 'zero') {
                    return $query->where('supply_quantity', '<=', 0);
                }
            })
            ->when($request->category_id, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->company_id, function ($query, $companyId) {
                return $query->where('company_id', $companyId);
            });

        $products = $query->orderBy('name')->get();

        return view('inventory-card.print', [
            'products' => $products,
            'date' => now()->format('Y-m-d')
        ]);
    }
} 