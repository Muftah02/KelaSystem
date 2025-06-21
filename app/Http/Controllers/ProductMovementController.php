<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SupplyItem;
use App\Models\DisbursementItem;
use Illuminate\Http\Request;

class ProductMovementController extends Controller
{
    /**
     * عرض صفحة حركة المنتج
     */
    public function index()
    {
        // جلب جميع المنتجات مع العلاقات المطلوبة
        $products = Product::with(['company', 'category', 'unitType'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'company' => $product->company->name,
                    'category' => $product->category->name,
                    'unit' => $product->unitType->name
                ];
            });

        return view('product-movements.index', compact('products'));
    }

    /**
     * عرض حركات منتج معين
     */
    public function show(Request $request, Product $product)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        // جلب حركات التوريد
        $supplyMovements = $product->supplyItems()
            ->with('supply')
            ->when($request->start_date, function ($query) use ($request) {
                return $query->whereHas('supply', function ($q) use ($request) {
                    $q->where('supply_date', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($query) use ($request) {
                return $query->whereHas('supply', function ($q) use ($request) {
                    $q->where('supply_date', '<=', $request->end_date);
                });
            })
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->supply->supply_date,
                    'type' => 'توريد',
                    'quantity' => $item->quantity,
                    'reference_id' => $item->supply_id,
                    'reference_type' => 'supply'
                ];
            });

        // جلب حركات الصرف
        $disbursementMovements = $product->disbursementItems()
            ->with('disbursement')
            ->when($request->start_date, function ($query) use ($request) {
                return $query->whereHas('disbursement', function ($q) use ($request) {
                    $q->where('disbursement_date', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($query) use ($request) {
                return $query->whereHas('disbursement', function ($q) use ($request) {
                    $q->where('disbursement_date', '<=', $request->end_date);
                });
            })
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->disbursement->disbursement_date,
                    'type' => 'صرف',
                    'quantity' => -$item->quantity, // سالب لأنها صرف
                    'reference_id' => $item->disbursement_id,
                    'reference_type' => 'disbursement'
                ];
            });

        // دمج الحركات وترتيبها حسب التاريخ
        $movements = $supplyMovements->concat($disbursementMovements)
            ->sortByDesc('date')
            ->values();

        // حساب الكمية المتبقية بعد كل حركة
        $remainingQuantity = 0;
        $movements = $movements->map(function ($movement) use (&$remainingQuantity) {
            $remainingQuantity += $movement['quantity'];
            $movement['remaining_quantity'] = $remainingQuantity;
            return $movement;
        });

        return view('product-movements.show', compact('product', 'movements'));
    }

    /**
     * البحث عن حركات المنتج
     */
    public function search(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $product = Product::findOrFail($request->product_id);

        // جلب حركات التوريد
        $supplyMovements = SupplyItem::with(['supply', 'product'])
            ->where('product_id', $request->product_id)
            ->when($request->start_date, function ($query) use ($request) {
                return $query->whereHas('supply', function ($q) use ($request) {
                    $q->where('supply_date', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($query) use ($request) {
                return $query->whereHas('supply', function ($q) use ($request) {
                    $q->where('supply_date', '<=', $request->end_date);
                });
            })
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->supply->supply_date,
                    'type' => 'توريد',
                    'quantity' => $item->quantity,
                    'reference_id' => $item->supply_id,
                    'reference_type' => 'supply'
                ];
            });

        // جلب حركات الصرف
        $disbursementMovements = DisbursementItem::with(['disbursement', 'product'])
            ->where('product_id', $request->product_id)
            ->when($request->start_date, function ($query) use ($request) {
                return $query->whereHas('disbursement', function ($q) use ($request) {
                    $q->where('disbursement_date', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($query) use ($request) {
                return $query->whereHas('disbursement', function ($q) use ($request) {
                    $q->where('disbursement_date', '<=', $request->end_date);
                });
            })
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->disbursement->disbursement_date,
                    'type' => 'صرف',
                    'quantity' => -$item->quantity,
                    'reference_id' => $item->disbursement_id,
                    'reference_type' => 'disbursement'
                ];
            });

        // دمج وترتيب الحركات
        $movements = $supplyMovements->concat($disbursementMovements)
            ->sortBy('date')
            ->values();

        // حساب الكمية المتبقية
        $remainingQuantity = 0;
        $movements = $movements->map(function ($movement) use (&$remainingQuantity) {
            $remainingQuantity += $movement['quantity'];
            $movement['remaining_quantity'] = $remainingQuantity;
            return $movement;
        });

        return response()->json([
            'product' => $product->only(['id', 'name']),
            'movements' => $movements
        ]);
    }
} 