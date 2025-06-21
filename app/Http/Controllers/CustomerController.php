<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'تم إضافة الزبون بنجاح');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'تم تحديث الزبون بنجاح');
    }

    public function destroy(Customer $customer)
    {
        try {
            // التحقق من وجود فواتير صرف مرتبطة بالعميل
            if ($customer->disbursements()->exists()) {
                $count = $customer->disbursements()->count();
                return redirect()->route('customers.index')
                    ->with('error', "لا يمكن حذف العميل لارتباطه بـ {$count} فاتورة صرف");
            }

            $customer->delete();
            return redirect()->route('customers.index')
                ->with('success', 'تم حذف العميل بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')
                ->with('error', 'حدث خطأ أثناء حذف العميل: ' . $e->getMessage());
        }
    }
} 