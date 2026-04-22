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
use App\Http\Controllers\Frontend\BrandController as FrontBrandController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\RefundController;
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

        // Hoàn hàng — chỉ xem, Admin mới có quyền sửa trạng thái
        Route::get('/returns',          [ShipperReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/{return}', [ShipperReturnController::class, 'show'])->name('returns.show');

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

        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('banners', BannerController::class);
        Route::delete('banners/{banner}/image', [BannerController::class, 'deleteImage'])->name('banners.image-delete');

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
Route::get('/lien-he', [PageController::class, 'contact'])->name('page.contact');

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

// Bảng alias viết tắt phổ biến → từ khóa gốc
$searchAliasMap = [
    'ip'      => 'iphone',
    'iph'     => 'iphone',
    'ss'      => 'samsung',
    'sam'     => 'samsung',
    'xm'      => 'xiaomi',
    'mi'      => 'xiaomi',
    'hw'      => 'huawei',
    'opp'     => 'oppo',
    'nk'      => 'nokia',
    'lg'      => 'lg',
    'vv'      => 'vivo',
    'rl'      => 'realme',
    'mb'      => 'macbook',
    'mac'     => 'macbook',
    'dell'    => 'dell',
    'hp'      => 'hp',
    'aw'      => 'apple watch',
    'ap'      => 'airpods',
    'apd'     => 'airpods',
    'tb'      => 'tablet',
    'mtb'         => 'máy tính bảng',
    'dt'          => 'điện thoại',
    'lt'          => 'laptop',
    'pk'          => 'phụ kiện',
    'sac'         => 'sạc',
    'op'          => 'ốp lưng',
    'cap'         => 'cáp',
    'tn'          => 'tai nghe',
    // Viết liền không dấu
    'dienthoai'   => 'điện thoại',
    'maytinhbang' => 'máy tính bảng',
    'phukien'     => 'phụ kiện',
    'oplung'      => 'ốp lưng',
    'tainghe'     => 'tai nghe',
];

Route::get('/api/search', function (Request $request) use ($searchAliasMap) {
    $q = trim($request->get('q', ''));
    if (!$q) return response()->json([]);

    $ascii = Str::ascii($q);
    $lowerQ = Str::lower($q);

    // Expand viết tắt: "ip17" hoặc "ip 14" → detect prefix → expand
    $expandedTerms = [$q, $ascii];

    // Thêm slug format: "dien thoai" → "dien-thoai" để match slug trong DB
    $asciiSlug = Str::slug($q);
    if ($asciiSlug) {
        $expandedTerms[] = $asciiSlug;
    }

    // Thêm phiên bản xóa dấu cách: "lap top" → "laptop"
    $noSpaces = str_replace(' ', '', $lowerQ);
    if ($noSpaces !== $lowerQ) {
        $expandedTerms[] = $noSpaces;
        $expandedTerms[] = Str::ascii($noSpaces);
    }

    // Tách phần chữ và phần số (có/không dấu cách): "ip14" hoặc "ip 14" → prefix="ip", suffix="14"
    if (preg_match('/^([a-zA-Z]+)\s*(\d+.*)$/', $lowerQ, $matches)) {
        $prefix = $matches[1];
        $suffix = $matches[2];
        if (isset($searchAliasMap[$prefix])) {
            $expanded = $searchAliasMap[$prefix] . ' ' . $suffix;
            $expandedTerms[] = $expanded;
            $expandedTerms[] = Str::ascii($expanded);
            $expandedTerms[] = Str::slug($expanded);
        }
    }

    // Cũng check toàn bộ chuỗi (không có số) có phải alias không
    $trimmedQ = trim($lowerQ);
    if (isset($searchAliasMap[$trimmedQ])) {
        $expandedTerms[] = $searchAliasMap[$trimmedQ];
    }

    $expandedTerms = array_unique(array_filter($expandedTerms));

    // Tìm theo tên/slug sản phẩm + tên/slug danh mục + tên/slug thương hiệu (tất cả cùng lúc)
    // Tìm sản phẩm khớp
    $products = \App\Models\Product::query()
        ->with(['variants', 'category'])
        ->where('status', 1)
        ->where(function ($query) use ($expandedTerms) {
            foreach ($expandedTerms as $term) {
                // Tìm theo tên/slug sản phẩm
                $query->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%");
            }
            // Tìm theo danh mục
            $query->orWhereHas('category', function ($catQ) use ($expandedTerms) {
                $catQ->where(function ($q) use ($expandedTerms) {
                    foreach ($expandedTerms as $term) {
                        $q->orWhere('name', 'like', "%{$term}%")
                            ->orWhere('slug', 'like', "%{$term}%");
                    }
                });
            });
            // Tìm theo thương hiệu
            $query->orWhereHas('brand', function ($brandQ) use ($expandedTerms) {
                $brandQ->where(function ($q) use ($expandedTerms) {
                    foreach ($expandedTerms as $term) {
                        $q->orWhere('name', 'like', "%{$term}%")
                            ->orWhere('slug', 'like', "%{$term}%");
                    }
                });
            });
        })
        ->limit(8)
        ->get();

    // Tìm danh mục khớp
    $categories = \App\Models\Category::where('status', 1)
        ->where(function ($query) use ($expandedTerms) {
            foreach ($expandedTerms as $term) {
                $query->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%");
            }
        })
        ->limit(3)->get();

    // Tìm thương hiệu khớp
    $brands = \App\Models\Brand::where('status', 1)
        ->where(function ($query) use ($expandedTerms) {
            foreach ($expandedTerms as $term) {
                $query->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%");
            }
        })
        ->limit(3)->get();

    return response()->json([
        'products' => $products->map(function ($product) {
            $activeVariants = $product->variants->where('status', 1);
            $salePrice = $activeVariants->min('sale_price');
            $basePrice = $activeVariants->min('price');

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => ($product->category ? $product->category->slug : 'san-pham') . '/' . $product->slug,
                'price' => $basePrice ?? $product->price ?? 0,
                'sale_price' => $salePrice,
                'category' => $product->category?->name ?? 'Sản phẩm',
                'image' => $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/no-image.jpg'),
            ];
        }),
        'categories' => $categories->map(fn($c) => [
            'name' => $c->name,
            'slug' => $c->slug,
            'icon' => $c->icon
        ]),
        'brands' => $brands->map(fn($b) => [
            'name' => $b->name,
            'slug' => $b->slug,
            'logo' => $b->logo ? asset('storage/' . $b->logo) : null
        ])
    ]);
});

/*
|--------------------------------------------------------------------------
| API SEARCH SUGGESTIONS (Gợi ý khi focus thanh tìm kiếm)
|--------------------------------------------------------------------------
*/
Route::get('/api/search/suggestions', function () {
    // Sản phẩm bán chạy / nổi bật
    $featured = \App\Models\Product::query()
        ->with(['variants', 'category'])
        ->where('status', 1)
        ->orderByDesc('is_featured')
        ->orderByDesc('sold_count')
        ->limit(6)
        ->get();

    $products = $featured->map(function ($product) {
        $activeVariants = $product->variants->where('status', 1);
        $salePrice = $activeVariants->min('sale_price');
        $basePrice = $activeVariants->min('price');

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => ($product->category ? $product->category->slug : 'san-pham') . '/' . $product->slug,
            'price' => $basePrice ?? $product->price ?? 0,
            'sale_price' => $salePrice,
            'category' => $product->category?->name ?? 'Sản phẩm',
            'image' => $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/no-image.jpg'),
        ];
    });

    // Danh mục gợi ý (lấy từ database)
    $categories = \App\Models\Category::where('status', 1)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->map(function ($cat) {
            return [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'icon' => $cat->icon,
                'image' => $cat->image ? asset('storage/' . $cat->image) : null,
            ];
        });

    // Thương hiệu (lấy từ database)
    $brands = \App\Models\Brand::where('status', 1)
        ->orderBy('name')
        ->get()
        ->map(function ($brand) {
            return [
                'name' => $brand->name,
                'slug' => $brand->slug,
                'logo' => $brand->logo ? asset('storage/' . $brand->logo) : null,
            ];
        });

    return response()->json([
        'products' => $products,
        'categories' => $categories,
        'brands' => $brands,
    ]);
});

// 🔥 CHI TIẾT SẢN PHẨM (LUÔN ĐỂ CUỐI CÙNG ĐỂ KHÔNG CHẶN CÁC ROUTE KHÁC)
Route::get('/{categorySlug}/{productSlug}', [FrontProductController::class, 'show'])->name('products.show');