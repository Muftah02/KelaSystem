<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->get();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')
            ->with('success', 'تم إضافة الشركة بنجاح');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $company->update($request->all());

        return redirect()->route('companies.index')
            ->with('success', 'تم تحديث الشركة بنجاح');
    }

    public function destroy(Company $company)
    {
        try {
            // التحقق من وجود منتجات مرتبطة بالشركة
            if ($company->products()->exists()) {
                $count = $company->products()->count();
                return redirect()->route('companies.index')
                    ->with('error', "لا يمكن حذف الشركة لارتباطها بـ {$count} منتج");
            }

            $company->delete();
            return redirect()->route('companies.index')
                ->with('success', 'تم حذف الشركة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('companies.index')
                ->with('error', 'حدث خطأ أثناء حذف الشركة: ' . $e->getMessage());
        }
    }
} 