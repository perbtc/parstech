<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\QuickSaleController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReportController;
// اضافه کردن کنترلرهای جدید
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PreInvoiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // مسیرهای بخش فروش
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/', [SalesController::class, 'store'])->name('store');
        Route::get('/returns', [SalesController::class, 'returns'])->name('returns');
        Route::post('/returns', [SalesController::class, 'storeReturn'])->name('returns.store');
    });

    // مسیرهای بخش حسابداری
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('/journal', [AccountingController::class, 'journal'])->name('journal');
        Route::get('/ledger', [AccountingController::class, 'ledger'])->name('ledger');
        Route::get('/balance', [AccountingController::class, 'balance'])->name('balance');
    });

    // مسیرهای بخش امور مالی
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/income', [FinancialController::class, 'income'])->name('income');
        Route::get('/expenses', [FinancialController::class, 'expenses'])->name('expenses');
        Route::get('/banking', [FinancialController::class, 'banking'])->name('banking');
        Route::get('/cheques', [FinancialController::class, 'cheques'])->name('cheques');
    });

    // مسیرهای بخش اشخاص
    Route::prefix('persons')->name('persons.')->group(function () {
        Route::get('/customers', [PersonController::class, 'customers'])->name('customers');
        Route::get('/suppliers', [PersonController::class, 'suppliers'])->name('suppliers');
    });

    // مسیرهای بخش گزارشات
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
    });

    // مسیرهای بخش تنظیمات
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/company', [SettingsController::class, 'company'])->name('company');
        Route::get('/users', [SettingsController::class, 'users'])->name('users');
    });

    // مسیرهای پیش فاکتور
    Route::resource('pre-invoices', PreInvoiceController::class);

    // مسیرهای موجود
    Route::resource('invoices', InvoiceController::class);
    Route::resource('products', ProductController::class);

    // انبار
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/in', [StockController::class, 'in'])->name('in');
        Route::get('/out', [StockController::class, 'out'])->name('out');
        Route::get('/transfer', [StockController::class, 'transfer'])->name('transfer');
    });

    // فروش سریع
    Route::get('/quick-sale', [QuickSaleController::class, 'index'])->name('quick-sale');
});

Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::resource('categories', CategoryController::class);

Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::resource('categories', CategoryController::class);
Route::get('/brands/create', [App\Http\Controllers\BrandController::class, 'create'])->name('brands.create');
Route::post('/brands', [App\Http\Controllers\BrandController::class, 'store'])->name('brands.store');
Route::post('/products/upload', [App\Http\Controllers\ProductController::class, 'upload'])->name('products.upload');
Route::post('/units', [App\Http\Controllers\UnitController::class, 'store'])->name('units.store');

Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::resource('services', \App\Http\Controllers\ServiceController::class);



require __DIR__.'/auth.php';
