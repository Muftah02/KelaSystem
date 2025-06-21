<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        try {
            // التحقق من وجود منتجات مرتبطة بالتصنيف
            if ($category->products()->exists()) {
                $count = $category->products()->count();
                return redirect()->route('categories.index')
                    ->with('error', "لا يمكن حذف التصنيف لارتباطه بـ {$count} منتج");
            }

            $category->delete();
            return redirect()->route('categories.index')
                ->with('success', 'تم حذف التصنيف بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                ->with('error', 'حدث خطأ أثناء حذف التصنيف: ' . $e->getMessage());
        }
    }
} 