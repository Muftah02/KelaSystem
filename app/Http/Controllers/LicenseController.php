<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::with('client')
            ->orderBy('end_date', 'desc')
            ->get();

        return view('licenses.index', compact('licenses'));
    }

    public function create()
    {
        $clients = Client::with(['factories' => function($query) {
            $query->select('id', 'client_id', 'name')
                  ->orderBy('name');
        }])
        ->orderBy('full_name')
        ->get();

        $clientsForDropdown = $clients->map(function($client) {
            return [
                'id' => $client->id,
                'name' => $client->full_name,
                'factories' => $client->factories->map(function($factory) {
                    return [
                        'id' => $factory->id,
                        'name' => $factory->name
                    ];
                })->values()->toArray()
            ];
        })->values()->toArray();

        // للتحقق من البيانات
        Log::info('Clients for dropdown:', [
            'count' => count($clientsForDropdown),
            'data' => $clientsForDropdown
        ]);

        return view('licenses.create', compact('clientsForDropdown'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'factory_id' => 'required|exists:factories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        License::create($validated);

        return redirect()->route('licenses.index')
            ->with('success', 'تم إضافة الترخيص بنجاح');
    }

    public function edit(License $license)
    {
        $clients = Client::with(['factories' => function($query) {
            $query->select('id', 'client_id', 'name');
        }])->orderBy('full_name')->get();
        return view('licenses.edit', compact('license', 'clients'));
    }

    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'factory_id' => 'required|exists:factories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $license->update($validated);

        return redirect()->route('licenses.index')
            ->with('success', 'تم تحديث الترخيص بنجاح');
    }

    public function destroy(License $license)
    {
        $license->delete();

        return redirect()->route('licenses.index')
            ->with('success', 'تم حذف الترخيص بنجاح');
    }

    public function print(License $license)
    {
        return view('licenses.print', compact('license'));
    }

    public function clientsByLicenseStatus()
    {
        $clients = Client::with(['licenses' => function ($query) {
            $query->orderBy('end_date', 'desc');
        }])->get();

        $now = now();

        // العملاء ذوي التراخيص السارية (أكثر من 10 أيام متبقي)
        $clientsWithActiveLicenses = $clients->filter(function ($client) use ($now) {
            return $client->licenses->contains(function ($license) use ($now) {
                return $license->end_date > $now->copy();
            });
        });

        // العملاء ذوي التراخيص المنتهية
        $clientsWithExpiredLicenses = $clients->filter(function ($client) use ($now) {
            return $client->licenses->isNotEmpty() && 
                   $client->licenses->every(function ($license) use ($now) {
                       return $license->end_date < $now;
                   });
        });

        // العملاء ذوي التراخيص القريبة الانتهاء (أقل من 10 أيام متبقي)
        $clientsWithExpiringLicenses = $clients->filter(function ($client) use ($now) {
            return $client->licenses->contains(function ($license) use ($now) {
                return $license->end_date >= $now && 
                       $license->end_date <= $now->copy()->addDays(30);
            });
        });

        // العملاء بدون تراخيص
        $clientsWithoutLicenses = $clients->filter(function ($client) {
            return $client->licenses->isEmpty();
        });

        return view('licenses.clients-status', compact(
            'clientsWithActiveLicenses',
            'clientsWithExpiredLicenses',
            'clientsWithExpiringLicenses',
            'clientsWithoutLicenses'
        ));
    }

    /**
     * عرض العملاء الذين لديهم تراخيص سارية
     */
    public function activeLicenses()
    {
        $clientsWithActiveLicenses = Client::whereHas('licenses', function ($query) {
            $query->where('end_date', '>', now());
        })->with('active_license')->get();

        return view('licenses.active-licenses', compact('clientsWithActiveLicenses'));
    }

    /**
     * عرض العملاء الذين لديهم تراخيص منتهية
     */
    public function expiredLicenses()
    {
        $clientsWithExpiredLicenses = Client::whereHas('licenses', function ($query) {
            $query->where('end_date', '<', now());
        })->with(['licenses' => function ($query) {
            $query->where('end_date', '<', now())->orderBy('end_date', 'desc');
        }])->get();

        return view('licenses.expired-licenses', compact('clientsWithExpiredLicenses'));
    }

    /**
     * عرض العملاء الذين تراخيصهم على وشك الانتهاء (خلال 30 يوم)
     */
    public function expiringLicenses()
    {
        $clientsWithExpiringLicenses = Client::whereHas('licenses', function ($query) {
            $query->where('end_date', '>', now())
                  ->where('end_date', '<=', now()->addDays(30));
        })->with(['licenses' => function ($query) {
            $query->where('end_date', '>', now())
                  ->where('end_date', '<=', now()->addDays(30))
                  ->orderBy('end_date', 'asc');
        }])->get();

        return view('licenses.expiring-licenses', compact('clientsWithExpiringLicenses'));
    }

    /**
     * عرض العملاء الذين لا يملكون تراخيص
     */
    public function noLicenses()
    {
        $clientsWithoutLicenses = Client::whereDoesntHave('licenses')->get();
        return view('licenses.no-licenses', compact('clientsWithoutLicenses'));
    }
} 