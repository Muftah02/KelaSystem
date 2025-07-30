<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // فلترة حسب الفئة
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب الحالة
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // فلترة المنتجات النشطة فقط
        if ($request->has('active_only')) {
            $query->active();
        }

        // فلترة المنتجات المميزة
        if ($request->has('featured_only')) {
            $query->featured();
        }

        // فلترة المنتجات المتوفرة في المخزون
        if ($request->has('in_stock_only')) {
            $query->inStock();
        }

        // ترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'track_quantity' => 'boolean',
            'status' => 'in:active,inactive,discontinued'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $productData = $request->except(['images']);
        
        // معالجة الصور
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $productData['images'] = $images;
        }

        $product = Product::create($productData);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product->load('category')
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'price' => 'sometimes|required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'track_quantity' => 'boolean',
            'status' => 'in:active,inactive,discontinued'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $productData = $request->except(['images']);

        // معالجة الصور الجديدة
        if ($request->hasFile('images')) {
            // حذف الصور القديمة
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $productData['images'] = $images;
        }

        $product->update($productData);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('category')
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // حذف الصور
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'operation' => 'required|in:increase,decrease',
            'reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $success = $product->updateStock($request->quantity, $request->operation);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'data' => [
                'product' => $product->fresh(),
                'new_stock' => $product->stock_quantity
            ]
        ]);
    }

    public function lowStock()
    {
        $products = Product::lowStock()->with('category')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}