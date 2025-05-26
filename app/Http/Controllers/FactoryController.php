<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factory;
use App\Models\Client;
use Termwind\Components\Dd;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $factories = Factory::with('client')->latest()->paginate(10); // جلب بيانات العميل المرتبط
        return view('factories.index', compact('factories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        return view('factories.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'machine_type' => 'required|in:آلي,نصف آلي,يدوي',
            'reservation_type' => 'required|in:شهري,سنوي',
            'factory_area' => 'required|numeric|min:0',
            'specified_ton_quantity' => 'required|numeric|min:0',
            'map_location' => 'required|string'
        ]);

        Factory::create($validated);

        return redirect()->route('factories.index')
            ->with('success', 'تم إضافة المصنع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $factory = Factory::findOrFail($id);
        return view('factories.show', compact('factory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // app/Http/Controllers/FactoryController.php

    public function edit(Factory $factory)
    {
        $factory->load('client'); // تأكد من تحميل العلاقة client

        return view('factories.edit', compact('factory'));
    }



    public function update(Request $request, Factory $factory)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id', // أضفنا التحقق من العميل
            'name' => 'required|string|max:255',
            'machine_type' => 'required|in:آلي,نصف آلي,يدوي',
            'reservation_type' => 'required|in:شهري,سنوي',
            'factory_area' => 'required|numeric|min:0',
            'specified_ton_quantity' => 'required|numeric|min:0',
            'map_location' => 'required|string'
        ]);

        $factory->update($validated);

        return redirect()->route('factories.index') // عدلنا مسار التوجيه
            ->with('success', 'تم تحديث بيانات المصنع بنجاح');
    }

    public function destroy(Factory $factory)
    {
        $factory->delete();
        return back()->with('success', 'تم حذف المصنع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
}
