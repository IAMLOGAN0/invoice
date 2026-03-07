<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings Route
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    
    // Shop Settings Route
    Route::patch('/shop', [ShopController::class, 'update'])->name('shop.update');

    // Invoice Routes
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('invoices.downloadPdf');

    // Product Routes
    Route::resource('products', ProductController::class);

    // Coupon Routes
    Route::resource('coupons', CouponController::class);

    // Customer Routes
    Route::resource('customers', CustomerController::class);

    // Due Payment Routes
    Route::get('/dues', [DueController::class, 'index'])->name('dues.index');
    Route::get('/dues/{customer}', [DueController::class, 'show'])->name('dues.show');
    Route::post('/dues/{invoice}/settle', [DueController::class, 'settle'])->name('dues.settle');
});

require __DIR__.'/auth.php';