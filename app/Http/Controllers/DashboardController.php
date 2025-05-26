<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Factory;
use App\Models\License;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // الصفحة العامة للمستخدمين
    public function index()
    {
        // إحصائيات عامة
        $clientsCount = Client::count();
        $factoriesCount = Factory::count();
        
        // حساب التراخيص السارية والمنتهية باستخدام التواريخ
        $now = Carbon::now();
        $activeLicensesCount = License::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->count();
            
        $expiredLicensesCount = License::where('end_date', '<', $now)
            ->count();

        // التراخيص القريبة الانتهاء (خلال 30 يوم)
        $expiringLicenses = License::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('end_date', '<=', $now->copy()->addDays(30))
            ->with('client')
            ->get();

        // العملاء بدون ترخيص
        $clientsWithoutLicense = Client::whereDoesntHave('licenses', function ($query) use ($now) {
            $query->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now);
        })->get();

        return view('dashboard.index', compact(
            'clientsCount',
            'factoriesCount',
            'activeLicensesCount',
            'expiredLicensesCount',
            'expiringLicenses',
            'clientsWithoutLicense'
        ));
    }

    // الصفحة الخاصة بالمدير
    public function managerDashboard()
    {
        // يمكن إضافة منطق خاص بلوحة تحكم المدير هنا
        return view('dashboard.manager');
    }

    // الصفحة الخاصة بالمحاسب
    public function accountantDashboard()
    {
        // يمكن إضافة منطق خاص بلوحة تحكم المحاسب هنا
        return view('dashboard.accountant');
    }
}
