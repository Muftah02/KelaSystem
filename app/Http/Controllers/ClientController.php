<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Proxy;
use Illuminate\Support\Facades\DB;
class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }


public function store(Request $request)
{
    $request->validate([
        // بيانات العميل
        'membership_number' => 'required|unique:clients',
        'full_name' => 'required',
        'national_id' => 'required|unique:clients',
        'phone' => 'required',
        'city' => 'required',
        'address' => 'required',
        
        // بيانات المصانع
        'factories' => 'required|array|min:1',
        'factories.*.name' => 'required|string|max:255',
        'factories.*.machine_type' => 'required|in:آلي,نصف آلي,يدوي',
        'factories.*.reservation_type' => 'required|in:شهري,سنوي',
        'factories.*.factory_area' => 'required|numeric|min:0',
        'factories.*.specified_ton_quantity' => 'required|numeric|min:0',
        'factories.*.map_location' => 'required|string',
        
        // بيانات الموكل (اختياري)
        'proxy_full_name' => 'nullable',
        'proxy_national_id' => 'nullable|unique:proxies,national_id',
        'proxy_phone' => 'nullable',
        'proxy_city' => 'nullable',
        'proxy_address' => 'nullable'
    ]);

    DB::beginTransaction();
    try {
        // إنشاء العميل
        $client = Client::create($request->only([
            'membership_number', 'full_name', 'national_id', 
            'phone', 'city', 'address'
        ]));

        // إنشاء المصانع
        foreach ($request->factories as $factoryData) {
            $client->factories()->create($factoryData);
        }

        // إنشاء الموكل إذا وجدت بيانات
        if ($request->filled('proxy_full_name')) {
            $client->proxy()->create($request->only([
                'full_name', 'national_id', 'phone', 'city', 'address'
            ]));
        }

        DB::commit();
        
        return redirect()->route('clients.index')
            ->with('success', 'تم إضافة العميل ومصانعه بنجاح');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()
            ->withErrors(['error' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()]);
    }
}


    public function show(Client $client)
    {
        $factories = $client->factories; // جلب المصانع التابعة له
        return view('clients.show', compact('client', 'factories'));
    }


    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            // تحقق من بيانات العميل
            'membership_number' => 'required|unique:clients,membership_number,'.$client->id,
            'full_name' => 'required',
            'national_id' => 'required|unique:clients,national_id,'.$client->id,
            'phone' => 'required',
            'city' => 'required',
            'address' => 'required',
            
            // تحقق من بيانات المصانع
            'factories' => 'required|array',
            'factories.*.id' => 'sometimes|exists:factories,id',
            'factories.*.name' => 'required|string|max:255',
            'factories.*.machine_type' => 'required|in:آلي,نصف آلي,يدوي',
            'factories.*.reservation_type' => 'required|in:شهري,سنوي',
            'factories.*.factory_area' => 'required|numeric|min:0',
            'factories.*.specified_ton_quantity' => 'required|numeric|min:0',
            'factories.*.map_location' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            // تحديث بيانات العميل
            $client->update([
                'membership_number' => $validated['membership_number'],
                'full_name' => $validated['full_name'],
                'national_id' => $validated['national_id'],
                'phone' => $validated['phone'],
                'city' => $validated['city'],
                'address' => $validated['address']
            ]);

            // معالجة المصانع
            $existingFactories = collect($request->factories)
                ->filter(fn($f) => isset($f['id']))
                ->pluck('id');

            // حذف المصانع غير المرسلة
            $client->factories()
                ->whereNotIn('id', $existingFactories)
                ->delete();

            // تحديث أو إنشاء المصانع
            foreach ($request->factories as $factoryData) {
                if (isset($factoryData['id'])) {
                    // تحديث مصنع موجود
                    $client->factories()->where('id', $factoryData['id'])->update([
                        'name' => $factoryData['name'],
                        'machine_type' => $factoryData['machine_type'],
                        'reservation_type' => $factoryData['reservation_type'],
                        'factory_area' => $factoryData['factory_area'],
                        'specified_ton_quantity' => $factoryData['specified_ton_quantity'],
                        'map_location' => $factoryData['map_location']
                    ]);
                } else {
                    // إنشاء مصنع جديد
                    $client->factories()->create([
                        'name' => $factoryData['name'],
                        'machine_type' => $factoryData['machine_type'],
                        'reservation_type' => $factoryData['reservation_type'],
                        'factory_area' => $factoryData['factory_area'],
                        'specified_ton_quantity' => $factoryData['specified_ton_quantity'],
                        'map_location' => $factoryData['map_location']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('clients.index')->with('success', 'تم تحديث بيانات العميل ومصانعه بنجاح');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'حدث خطأ: '.$e->getMessage()]);
        }
    }
    //destroy
    public function destroy(Client $client)
    {
        // حذف الموكل إذا كان موجودًا
        if ($client->proxy) {
            $client->proxy()->delete();
        }

        // حذف العميل
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'تم حذف العميل بنجاح.');
    }
}
