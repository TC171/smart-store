<?php

use Illuminate\Support\Facades\Route;
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
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

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

        Route::view('/profile', 'admin.profile')
            ->name('admin.profile');

        Route::view('/change-password', 'admin.password')
            ->name('admin.password');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('admin.logout');

        Route::post('/products/import', [ProductController::class, 'import'])
            ->name('products.import');
    });
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('customer.profile');
    })->name('profile');

    // Customer orders
    Route::get('/orders', function () {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    })->name('orders');

    Route::get('/orders/{order}', function (\App\Models\Order $order) {
        // Ensure user can only view their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        $order->load('items.variant.product');
        return view('customer.order-detail', compact('order'));
    })->name('order.detail');
});

/*
|--------------------------------------------------------------------------
| Frontend Shop Routes (PUBLIC)
|--------------------------------------------------------------------------
*/

use App\Models\Product;

// Trang chủ
Route::get('/', function () {
    $products = Product::where('status', 1)
        ->latest()
        ->take(8)
        ->get();

    return view('frontend.home', compact('products'));
});
Route::get('/products/{id}', function ($id) {
    $product = \App\Models\Product::findOrFail($id);

    return view('frontend.products.show', compact('product'));
});
Route::post('/cart/add', function () {

    $variantId = request('variant_id');

    $variant = \App\Models\ProductVariant::findOrFail($variantId);

    $cart = session()->get('cart', []);

    if (isset($cart[$variantId])) {
        $cart[$variantId]['quantity']++;
    } else {
        $cart[$variantId] = [
            'name' => $variant->name ?? 'Variant',
            'price' => $variant->price,
            'quantity' => 1
        ];
    }

    session()->put('cart', $cart);

    return back()->with('success', 'Đã thêm vào giỏ!');
});
Route::get('/cart', function () {
    return view('frontend.cart.index');
});

Route::get('/cart/remove/{id}', function ($id) {

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
    }

    session()->put('cart', $cart);

    return back();
});
Route::post('/cart/update/{id}', function ($id) {

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity'] = request('quantity');
    }

    session()->put('cart', $cart);

    return back();
});

// Trang checkout
Route::get('/checkout', function () {
    return view('frontend.checkout');
});

// Xử lý đặt hàng
Route::post('/checkout', function () {

    $cart = session('cart', []);

    if (count($cart) == 0) {
        return back()->with('error', 'Giỏ hàng trống');
    }

    $total = 0;

    foreach ($cart as $variantId => $item) {

    $variant = \App\Models\ProductVariant::find($variantId);

    $total += $variant->price * $item['quantity']; // ✅ đúng
}

    // Tạo đơn hàng
    $order = \App\Models\Order::create([
    'order_number' => 'ORD' . time(), // ✅ thêm dòng này
    'user_id' => auth()->id() ?? 1,
    'total_amount' => $total,
    'status' => 'pending',
    'payment_status' => 'unpaid',
    'grand_total' => $total,
]);

    // Tạo order items
    foreach ($cart as $variantId => $item) {

    $variant = \App\Models\ProductVariant::find($variantId); // ✅ thêm dòng này

    \App\Models\OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $variant->product_id, // ✅ đúng
        'price' => $variant->price, // ✅ đúng
        'quantity' => $item['quantity'],
    ]);
}

    // Xóa giỏ hàng
    session()->forget('cart');

    return redirect('/')->with('success', 'Đặt hàng thành công!');
});

