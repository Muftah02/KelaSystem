<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Products
    Route::apiResource('products', ProductController::class);
    Route::post('products/{id}/update-stock', [ProductController::class, 'updateStock']);
    Route::get('products-low-stock', [ProductController::class, 'lowStock']);
    
    // Customers
    Route::apiResource('customers', CustomerController::class);
    
    // Orders
    Route::apiResource('orders', OrderController::class);
    Route::post('orders/{id}/update-status', [OrderController::class, 'updateStatus']);
    Route::get('orders-today', [OrderController::class, 'todayOrders']);
    Route::get('orders/{id}/receipt', [OrderController::class, 'receipt']);
    
    // Dashboard stats
    Route::get('dashboard/stats', function () {
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_categories' => \App\Models\Category::count(),
            'total_customers' => \App\Models\Customer::count(),
            'total_orders' => \App\Models\Order::count(),
            'today_orders' => \App\Models\Order::today()->count(),
            'today_sales' => \App\Models\Order::today()->sum('total_amount'),
            'pending_orders' => \App\Models\Order::pending()->count(),
            'low_stock_products' => \App\Models\Product::lowStock()->count(),
            'monthly_sales' => \App\Models\Order::thisMonth()->sum('total_amount'),
            'top_selling_products' => \App\Models\Product::select('products.*')
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->selectRaw('products.*, SUM(order_items.quantity) as total_sold')
                ->groupBy('products.id')
                ->orderBy('total_sold', 'desc')
                ->limit(5)
                ->get(),
            'recent_orders' => \App\Models\Order::with(['customer', 'items'])
                ->latest()
                ->limit(5)
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    });
});

// Public routes (للمنتجات العامة إذا كان هناك متجر عام)
Route::get('public/products', [ProductController::class, 'index']);
Route::get('public/products/{id}', [ProductController::class, 'show']);
Route::get('public/categories', [CategoryController::class, 'index']);
