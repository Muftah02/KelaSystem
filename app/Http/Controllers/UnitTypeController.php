<?php

namespace App\Http\Controllers;

use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    public function index()
    {
        $unitTypes = UnitType::latest()->get();
        return view('unit-types.index', compact('unitTypes'));
    }

    public function create()
    {
        return view('unit-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        UnitType::create($request->all());

        return redirect()->route('unit-types.index')
            ->with('success', 'تم إضافة نوع الوحدة بنجاح');
    }

    public function edit(UnitType $unitType)
    {
        return view('unit-types.edit', compact('unitType'));
    }

    public function update(Request $request, UnitType $unitType)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $unitType->update($request->all());

        return redirect()->route('unit-types.index')
            ->with('success', 'تم تحديث نوع الوحدة بنجاح');
    }

    public function destroy(UnitType $unitType)
    {
        try {
            // التحقق من وجود منتجات مرتبطة بنوع الوحدة
            if ($unitType->products()->exists()) {
                $count = $unitType->products()->count();
                return redirect()->route('unit-types.index')
                    ->with('error', "لا يمكن حذف نوع الوحدة لارتباطه بـ {$count} منتج");
            }

            $unitType->delete();
            return redirect()->route('unit-types.index')
                ->with('success', 'تم حذف نوع الوحدة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('unit-types.index')
                ->with('error', 'حدث خطأ أثناء حذف نوع الوحدة: ' . $e->getMessage());
        }
    }
} 