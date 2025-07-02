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
use Illuminate\Support\Facades\Artisan;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UnitTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\DisbursementController;
use App\Http\Controllers\ProductMovementController;
use App\Http\Controllers\InventoryCardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\BackupController;
use Spatie\Backup\BackupDestination\Backup;

// المسارات العامة
Route::get('/', function () {
    return view('welcome');
});

// مسارات المصادقة
require __DIR__ . '/auth.php';


    // المسار الخاص بالمدير

    // المسار الخاص بالمحاسب

    // مسارات العملاء
    
    // مسارات المصانع
   
    // مسارات الملف الشخصي
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('unit-types', UnitTypeController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('supplies', SupplyController::class);
    Route::resource('disbursements', DisbursementController::class);
    Route::get('/product-movements', [ProductMovementController::class, 'index'])->name('product-movements.index');
    Route::post('/product-movements/search', [ProductMovementController::class, 'search'])->name('product-movements.search');
    Route::get('/search/companies', [ProductController::class, 'searchCompanies'])->name('search.companies');
    Route::get('/search/categories', [ProductController::class, 'searchCategories'])->name('search.categories');
    Route::get('/inventory-card', [InventoryCardController::class, 'index'])->name('inventory-card.index');
    Route::get('/supplies/{supply}/print', [SupplyController::class, 'print'])->name('supplies.print');
    Route::get('/disbursements/{disbursement}/print', [DisbursementController::class, 'print'])->name('disbursements.print');
    Route::get('/inventory-card/print', [InventoryCardController::class, 'print'])->name('inventory-card.print');

    // إضافة مسار النسخ الاحتياطي
    Route::get('/backup', [BackupController::class, 'create'])->name('backup');

    // صفحة المساعد الذكي
    Route::get('/assistant', function () {
        return view('assistant');
    })->name('assistant');
    
    // مسار API للمساعد الذكي
    Route::post('/api/ask', [App\Http\Controllers\AssistantController::class, 'handle'])->name('assistant.ask');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


