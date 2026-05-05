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
use App\Http\Controllers\Admin\AIAdminController;

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
use App\Http\Controllers\Frontend\BrandController as FrontBrandController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\RefundController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\Admin\ShipperController;
use App\Http\Controllers\Shipper\AuthController as ShipperAuthController;
use App\Http\Controllers\Shipper\DeliveryController;
use App\Http\Controllers\Shipper\ReturnController as ShipperReturnController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------

*/
use App\Http\Controllers\Frontend\AIController;

 

Route::post('/chat-ai', [AIController::class, 'chat']);
Route::get('/test-ai', [AIController::class, 'chat']);
Route::get('/ai/history', [AIController::class, 'history']);

// =================== SHIPPER PORTAL ROUTES ===================
Route::prefix('shipper')->name('shipper.')->group(function () {

    // Guest (chưa đăng nhập)
    Route::middleware('guest:shipper')->group(function () {
        Route::get('/login',  [ShipperAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [ShipperAuthController::class, 'login'])->name('login.post');
    });

    // Đã đăng nhập
    Route::middleware(['auth:shipper', 'shipper'])->group(function () {
        Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');

        Route::get('/deliveries',                    [DeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('/deliveries/{delivery}',         [DeliveryController::class, 'show'])->name('deliveries.show');
        Route::post('/deliveries/{delivery}/pickup', [DeliveryController::class, 'pickup'])->name('deliveries.pickup');
        Route::post('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.updateStatus');

        // Hoàn hàng — Shipper có thể cập nhật trạng thái quá trình hoàn
        Route::get('/returns',                          [ShipperReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/{return}',                 [ShipperReturnController::class, 'show'])->name('returns.show');
        Route::post('/returns/{return}/pickup',         [ShipperReturnController::class, 'confirmPickup'])->name('returns.pickup');
        Route::post('/returns/{return}/returning',      [ShipperReturnController::class, 'confirmReturning'])->name('returns.returning');
        Route::post('/returns/{return}/delivered',      [ShipperReturnController::class, 'confirmDelivered'])->name('returns.delivered');

        Route::post('/logout', [ShipperAuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    Route::middleware(['auth:admin', 'admin'])->group(function () {
        Route::post(
        'upload-image',
        [ProductController::class, 'uploadImage']
    )->name('upload.image');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
       Route::get('/revenue-chart', [DashboardController::class, 'revenueChart'])
    ->name('revenue.chart');

        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('banners', BannerController::class);
        Route::delete('banners/{banner}/image', [BannerController::class, 'deleteImage'])->name('banners.image-delete');

        Route::resource('post-categories', App\Http\Controllers\Admin\PostCategoryController::class);
        Route::resource('posts', App\Http\Controllers\Admin\PostController::class);
        Route::post('posts/upload-image', [App\Http\Controllers\Admin\PostController::class, 'uploadImage'])->name('posts.upload.image');

        Route::resource('coupons', CouponController::class)->except(['show']);

        // Cấu hình Route Order Admin chuẩn
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::patch('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');

        // Quản lý yêu cầu hoàn hàng
        Route::get('refunds', [AdminRefundController::class, 'index'])->name('refunds.index');
        Route::get('refunds/{refund}', [AdminRefundController::class, 'show'])->name('refunds.show');
        Route::post('refunds/{refund}/approve', [AdminRefundController::class, 'approve'])->name('refunds.approve');
        Route::post('refunds/{refund}/confirm', [AdminRefundController::class, 'confirmReceived'])->name('refunds.confirm');
        Route::post('refunds/{refund}/reject', [AdminRefundController::class, 'reject'])->name('refunds.reject');

        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('customers', CustomerController::class);
        Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);
        Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::resource('variants', ProductVariantController::class)->except(['show']);
        Route::resource('product-attributes', ProductAttributeController::class)->except(['show']);
        Route::resource('inventory-history', InventoryHistoryController::class)->except(['edit', 'update']);
        Route::resource('admins', AdminController::class);

        // =================== SHIPPER MANAGEMENT ===================
        Route::prefix('shippers')->name('shippers.')->group(function () {
            Route::get('/',       [ShipperController::class, 'index'])->name('index');
            Route::get('/create', [ShipperController::class, 'create'])->name('create');
            Route::post('/',      [ShipperController::class, 'store'])->name('store');
            Route::get('/{shipper}/edit', [ShipperController::class, 'edit'])->name('edit');
            Route::put('/{shipper}',      [ShipperController::class, 'update'])->name('update');
            Route::delete('/{shipper}',   [ShipperController::class, 'destroy'])->name('destroy');

            Route::get('/deliveries',       [ShipperController::class, 'deliveries'])->name('deliveries');
            Route::get('/assign',           [ShipperController::class, 'assign'])->name('assign');
            Route::post('/assign',          [ShipperController::class, 'assignStore'])->name('assign.store');
        });
        Route::view('profile', 'admin.profile')->name('profile');
        Route::view('password', 'admin.password')->name('password');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::prefix('ai')->name('ai.')->group(function () {

    // ⚙️ settings
    Route::get('/settings', [AIAdminController::class, 'settings'])->name('settings');
    Route::post('/toggle', [AIAdminController::class, 'toggle'])->name('toggle');

    // 👥 users chat
    Route::get('/users', [AIAdminController::class, 'users'])->name('users');

    // 💬 chat detail
    Route::get('/users/{id}', [AIAdminController::class, 'detail'])->name('detail');

});
    });
});

/*
|--------------------------------------------------------------------------
| AUTH (GUEST) - ĐƯA LÊN TRÊN ĐỂ TRÁNH LỖI 404 CHI TIẾT SẢN PHẨM
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [FrontAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [FrontAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [FrontAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [FrontAuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [FrontAuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [FrontAuthController::class, 'processForgotPassword'])->name('forgot-password.post');
});
Route::middleware(['auth:web', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', fn () => view('customer.dashboard'))->name('dashboard');

        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('order.detail');
        Route::post('/orders/{order}/reviews', [CustomerOrderController::class, 'storeReview'])->name('orders.reviews.store');

        Route::post('/logout', [FrontAuthController::class, 'logout'])->name('logout');
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

        Route::get('/dashboard', [ProfileController::class, 'edit'])->name('dashboard');

        Route::put('/dashboard', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/dashboard/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/dashboard/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('order.detail');
        Route::post('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/reviews', [CustomerOrderController::class, 'storeReview'])->name('orders.reviews.store');

        // Hoàn hàng / Hoàn tiền
        Route::get('/orders/{order}/refund', [RefundController::class, 'create'])->name('orders.refund.create');
        Route::post('/orders/{order}/refund', [RefundController::class, 'store'])->name('orders.refund.store');

        // ❤️ Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

        Route::post('/logout', [FrontAuthController::class, 'logout'])->name('logout');

        // 🔔 Notification API routes
        Route::get('/notifications', function () {
            $notifications = auth('web')->user()->notifications()->latest()->take(20)->get()->map(function ($n) {
                return [
                    'id'        => $n->id,
                    'read'      => !is_null($n->read_at),
                    'icon'      => $n->data['icon'] ?? '🔔',
                    'title'     => $n->data['title'] ?? '',
                    'body'      => $n->data['body'] ?? '',
                    'url'       => $n->data['url'] ?? '#',
                    'color'     => $n->data['color'] ?? 'gray',
                    'time'      => $n->created_at->diffForHumans(),
                ];
            });
            $unread = auth('web')->user()->unreadNotifications()->count();
            return response()->json(['notifications' => $notifications, 'unread' => $unread]);
        })->name('notifications.index');

        Route::post('/notifications/read-all', function () {
            auth('web')->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        })->name('notifications.read-all');

        Route::post('/notifications/{id}/read', function ($id) {
            $n = auth('web')->user()->notifications()->where('id', $id)->first();
            if ($n) $n->markAsRead();
            return response()->json(['success' => true]);
        })->name('notifications.read');
    });
    
/*
|--------------------------------------------------------------------------
| FRONTEND PUBLIC ROUTES
|--------------------------------------------------------------------------
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/tim-kiem', [HomeController::class, 'search'])->name('search');

// Giỏ hàng
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Thanh toán & Coupon (Bắt buộc đăng nhập)
Route::middleware('auth:web')->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.store');

    // 🔥 Coupon API Routes
    Route::get('/api/coupons', [CartController::class, 'getAvailableCoupons'])->name('coupon.list');
    Route::post('/api/apply-coupon', [CartController::class, 'applyCoupon'])->name('coupon.apply');

    // Route nhận kết quả VNPAY trả về
    Route::get('/vnpay/return', [CartController::class, 'vnpayReturn'])->name('vnpay.return');
});

/*
|--------------------------------------------------------------------------
| INFORMATION PAGES
|--------------------------------------------------------------------------
*/
Route::get('/ve-chung-toi', [PageController::class, 'about'])->name('page.about');
Route::get('/chinh-sach-bao-hanh', [PageController::class, 'warranty'])->name('page.warranty');
Route::get('/chinh-sach-doi-tra', [PageController::class, 'returnPolicy'])->name('page.return-policy');
Route::get('/chinh-sach-bao-mat', [PageController::class, 'privacy'])->name('page.privacy');
Route::get('/chinh-sach-van-chuyen', [PageController::class, 'shipping'])->name('page.shipping');
Route::get('/dieu-khoan-dich-vu', [PageController::class, 'terms'])->name('page.terms');
Route::get('/lien-he', [PageController::class, 'contact'])->name('page.contact');
Route::post('/lien-he', [PageController::class, 'submitContact'])->name('page.contact.submit');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// ❤️ Wishlist toggle (AJAX, yêu cầu đăng nhập)
Route::middleware(['auth:web'])->post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

/*
|--------------------------------------------------------------------------
| NEWS ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/tin-tuc', [\App\Http\Controllers\Frontend\NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/{slug}', [\App\Http\Controllers\Frontend\NewsController::class, 'show'])->name('news.show');

/*
|--------------------------------------------------------------------------
| CATEGORY & PRODUCT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/danh-muc/{slug}', [FrontCategoryController::class, 'products'])->name('category.products');
Route::get('/thuong-hieu/{slug}', [FrontBrandController::class, 'products'])->name('brand.products');
Route::get('/san-pham-noi-bat', [FrontProductController::class, 'featured'])->name('products.featured');

/*
|--------------------------------------------------------------------------
| API SEARCH (Nâng cấp: hỗ trợ viết tắt + fuzzy search)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Frontend\SearchController;

Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');

/*
|--------------------------------------------------------------------------
| API SEARCH SUGGESTIONS (Gợi ý khi focus thanh tìm kiếm)
|--------------------------------------------------------------------------
*/
Route::get('/api/search/suggestions', [SearchController::class, 'suggestions'])->name('api.search.suggestions');

// 🔥 CHI TIẾT SẢN PHẨM (LUÔN ĐỂ CUỐI CÙNG ĐỂ KHÔNG CHẶN CÁC ROUTE KHÁC)
Route::get('/{categorySlug}/{productSlug}', [FrontProductController::class, 'show'])->name('products.show');