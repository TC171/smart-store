<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryHistoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| FRONTEND CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Frontend\AuthController as FrontAuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CustomerOrderController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontProductController;
use App\Http\Controllers\Frontend\CategoryController as FrontCategoryController;
use App\Http\Controllers\Frontend\PageController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    Route::middleware(['auth:admin', 'admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('banners', BannerController::class);
        Route::delete('banners/{banner}/image', [BannerController::class, 'deleteImage'])->name('banners.image-delete');

        Route::resource('coupons', CouponController::class)->except(['show']);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');

        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('customers', CustomerController::class);
        Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);
        Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::resource('variants', ProductVariantController::class)->except(['show']);
        Route::resource('product-attributes', ProductAttributeController::class)->except(['show']);
        Route::resource('inventory-history', InventoryHistoryController::class)->except(['edit', 'update']);
        Route::resource('admins', AdminController::class);
        Route::view('profile', 'admin.profile')->name('profile');
        Route::view('password', 'admin.password')->name('password');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', fn () => view('customer.dashboard'))->name('dashboard');

        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('order.detail');

        Route::post('/logout', [FrontAuthController::class, 'logout'])->name('logout');
    });

/*
|--------------------------------------------------------------------------
| FRONTEND PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::middleware('auth:web')->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.store');
});

Route::get('/tim-kiem', [HomeController::class, 'search'])->name('search');

/*
|--------------------------------------------------------------------------
| INFORMATION PAGES
|--------------------------------------------------------------------------
*/
Route::get('/ve-chung-toi', [PageController::class, 'about'])->name('page.about');
Route::get('/chinh-sach-bao-hanh', [PageController::class, 'warranty'])->name('page.warranty');
Route::get('/chinh-sach-doi-tra', [PageController::class, 'returnPolicy'])->name('page.return-policy');
Route::get('/lien-he', [PageController::class, 'contact'])->name('page.contact');

/*
|--------------------------------------------------------------------------
| CATEGORY ROUTE (FIX CHÍNH)
|--------------------------------------------------------------------------
*/
Route::get('/danh-muc/{slug}', [FrontCategoryController::class, 'products'])
    ->name('category.products');

/*
|--------------------------------------------------------------------------
| PRODUCT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/san-pham/{slug}', [FrontProductController::class, 'show'])
    ->name('product.detail');

Route::get('/san-pham-noi-bat', [FrontProductController::class, 'featured'])
    ->name('products.featured');

/*
|--------------------------------------------------------------------------
| AUTH (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->group(function () {

    Route::get('/login', [FrontAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [FrontAuthController::class, 'login'])->name('login.post');

    Route::get('/register', [FrontAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [FrontAuthController::class, 'register'])->name('register.post');
});

// /*
// |--------------------------------------------------------------------------
// | API SEARCH
// |--------------------------------------------------------------------------
// */
// Route::get('/api/search', function (Request $request) {

//     $q = $request->get('q');

//     if (!$q) return [];

//     $ascii = Str::ascii($q);

//     $products = \App\Models\Product::query()
//         ->with(['variants', 'category'])
//         ->where('status', 1)
//         ->where(function ($query) use ($q, $ascii) {
//             $query->where('name', 'like', "%{$q}%")
//                   ->orWhere('name', 'like', "%{$ascii}%")
//                   ->orWhere('slug', 'like', "%{$q}%")
//                   ->orWhere('slug', 'like', "%{$ascii}%");
//         })
//         ->limit(6)
//         ->get();

//     // Fallback: nếu không tìm thấy bằng tên, bám vào danh mục điện thoại, laptop ...
//     if ($products->isEmpty()) {
//         $products = \App\Models\Product::query()
//             ->with(['variants', 'category'])
//             ->where('status', 1)
//             ->whereHas('category', function ($query) use ($q, $ascii) {
//                 $query->where('name', 'like', "%{$q}%")
//                       ->orWhere('name', 'like', "%{$ascii}%");
//             })
//             ->limit(6)
//             ->get();
//     }

//     return $products
//         ->map(function ($product) {

//             $variantPrice = $product->variants->min('price');
//             $basePrice = $product->sale_price ? $product->sale_price : $product->price;
//             $originalPrice = $product->price;

//             return [
//                 'id' => $product->id,
//                 'name' => $product->name,
//                 'slug' => $product->slug,
//                 'price' => $basePrice ?? $variantPrice ?? 0,
//                 'sale_price' => $product->sale_price ?: null,
//                 'original_price' => $originalPrice ?: null,
//                 'stock' => $product->stock ?? 0,
//                 'category' => $product->category?->name ?? null,
//                 'image' => $product->image ? asset($product->image) : asset('images/no-image.jpg'),
//             ];
//         });
// });