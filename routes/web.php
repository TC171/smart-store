<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;

Route::prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Guest Routes (Chưa đăng nhập)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])
            ->name('admin.login');

        Route::post('/login', [AuthController::class, 'login'])
            ->name('admin.login.post');
    });

    /*
    |--------------------------------------------------------------------------
    | Auth Routes (Đã đăng nhập)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Products
        Route::resource('products', ProductController::class);

        // Categories
        Route::resource('categories', CategoryController::class);
        // Orders
       Route::resource('orders', OrderController::class)->only(['index','show','update']);
        // Profile
        Route::view('/profile', 'admin.profile')
            ->name('admin.profile');

        // Change Password
        Route::view('/change-password', 'admin.password')
            ->name('admin.password');

        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('admin.logout');
        Route::post('/products/import', [ProductController::class, 'import'])
            ->name('products.import');
    });
});
