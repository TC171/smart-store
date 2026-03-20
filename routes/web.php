<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InventoryHistoryController;

/*
|--------------------------------------------------------------------------
| FRONTEND CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontProductController;
use App\Http\Controllers\Frontend\AuthController as FrontAuthController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    });

    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('banners', BannerController::class);
        Route::delete('banners/{banner}/image', [BannerController::class, 'deleteImage'])->name('banners.image-delete');

        Route::resource('coupons', CouponController::class);
        Route::resource('product-attributes', ProductAttributeController::class);
        Route::resource('variants', ProductVariantController::class);

        Route::resource('orders', OrderController::class);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::patch('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');

        Route::resource('inventory-history', InventoryHistoryController::class);

        Route::resource('reviews', ReviewController::class);
        Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');

        Route::resource('users', UserController::class);
        Route::resource('admins', AdminController::class);
        Route::resource('customers', CustomerController::class);

        Route::view('/profile', 'admin.profile')->name('admin.profile');
        Route::view('/change-password', 'admin.password')->name('admin.password');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    });
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES (CHỈ CUSTOMER ĐƯỢC VÀO)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('customer.dashboard'))->name('dashboard');

        Route::get('/profile', fn() => view('customer.profile'))->name('profile');

        Route::get('/orders', function () {
            $orders = auth()->user()->orders()->latest()->paginate(10);
            return view('customer.orders', compact('orders'));
        })->name('orders');

        Route::get('/orders/{order}', function (\App\Models\Order $order) {
            if ($order->user_id !== auth()->id()) abort(403);
            $order->load('items.variant.product');
            return view('customer.order-detail', compact('order'));
        })->name('order.detail');

        // logout user
        Route::post('/logout', [FrontAuthController::class, 'logout'])->name('logout');
    });

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

// Public (chưa login vẫn vào được)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [FrontProductController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| AUTH USER (KHÔNG LOGIN MỚI VÀO ĐƯỢC)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [FrontAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [FrontAuthController::class, 'login'])->name('login.post');

    Route::get('/register', [FrontAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [FrontAuthController::class, 'register'])->name('register.post');
});
