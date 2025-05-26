<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FactoryController;
use App\Models\Factory;
use App\Models\Customer;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LicenseController;

// المسارات العامة
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// مسارات المصادقة
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // المسار الخاص بالمدير
    Route::middleware('role:manager')->get('/manager-dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');

    // المسار الخاص بالمحاسب
    Route::middleware('role:accountant')->get('/accountant-dashboard', [DashboardController::class, 'accountantDashboard'])->name('accountant.dashboard');

    // مسارات العملاء
    Route::resource('clients', ClientController::class);

    // مسارات المصانع
    Route::resource('factories', FactoryController::class);

    // مسارات التراخيص
    Route::resource('licenses', LicenseController::class);
    Route::get('licenses/{license}/print', [LicenseController::class, 'print'])->name('licenses.print');
    Route::get('licenses/clients/status', [LicenseController::class, 'clientsByLicenseStatus'])->name('licenses.clients-status');
    
    // مسارات تصنيف العملاء حسب حالة الترخيص
    Route::get('licenses/clients/active', [LicenseController::class, 'activeLicenses'])->name('licenses.active');
    Route::get('licenses/clients/expired', [LicenseController::class, 'expiredLicenses'])->name('licenses.expired');
    Route::get('licenses/clients/expiring', [LicenseController::class, 'expiringLicenses'])->name('licenses.expiring');
    Route::get('licenses/clients/no-licenses', [LicenseController::class, 'noLicenses'])->name('licenses.no-licenses');

    // مسارات الملف الشخصي
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth'])->get('/dashboard', function () {
//     return view('dashboard');  // يمكنك تخصيص هذه الصفحة كما تريد
// })->name('dashboard');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
