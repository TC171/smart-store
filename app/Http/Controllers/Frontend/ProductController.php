<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($categorySlug, $productSlug)
    {
        $product = Product::with([
            'category',
            'brand',
            'variants' => function ($q) {
                $q->where('status', 1);
            },
            'reviews' => function ($q) {
                $q->where('is_approved', 1)->latest();
            },
            'reviews.user'
        ])
        ->where('slug', $productSlug)
        ->where('status', 1)
        ->firstOrFail();

        if (!$product->category || $product->category->slug !== $categorySlug) {
            abort(404);
        }

        $this->authorize('view', $product);

        $minPrice = $product->variants->min(fn($v) => $v->sale_price ?? $v->price) ?? 0;
        $maxPrice = $product->variants->max(fn($v) => $v->sale_price ?? $v->price) ?? 0;
        $totalStock = $product->variants->sum('stock');

        $relatedProducts = Product::with('variants')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->take(5)
            ->get();

        // 🔥 SỬA LỖI MÃ GIẢM GIÁ: Dùng trực tiếp lệnh SQL NOW() và bao trọn trường hợp NULL
        $coupons = Coupon::where('status', 1)
            ->whereRaw('(starts_at IS NULL OR starts_at <= NOW())')
            ->whereRaw('(expires_at IS NULL OR expires_at >= NOW())')
            ->whereRaw('(usage_limit IS NULL OR used_count < usage_limit)')
            ->get();

        $reviews = $product->reviews;
        $avgRating = round($reviews->avg('rating'), 1);
        $totalReviews = $reviews->count();

        // 🔥 map category -> view
        $viewMap = [
            'dien-thoai' => 'frontend.products.types.phone',
            'laptop' => 'frontend.products.types.laptop',
            'phu-kien' => 'frontend.products.types.accessory',
            'tablet' => 'frontend.products.types.tablet',
            'may-tinh-bang' => 'frontend.products.types.tablet',
        ];

        $view = $viewMap[$categorySlug] ?? 'frontend.products.types.default';

        return view($view, compact(
            'product',
            'minPrice',
            'maxPrice',
            'totalStock',
            'relatedProducts',
            'coupons',
            'reviews',
            'avgRating',
            'totalReviews'
        ));
    }

    public function featured(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::where('status', 1)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ]);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->filled('price_range') && $request->price_range !== 'all') {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('status', 1);

                switch ($request->price_range) {
                    case 'under_2m':
                        $q->whereRaw('COALESCE(sale_price, price) < ?', [2000000]);
                        break;
                    case '2m_4m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [2000000, 4000000]);
                        break;
                    case '4m_7m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [4000000, 7000000]);
                        break;
                    case '7m_13m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [7000000, 13000000]);
                        break;
                    case '13m_20m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [13000000, 20000000]);
                        break;
                    case 'over_20m':
                        $q->whereRaw('COALESCE(sale_price, price) > ?', [20000000]);
                        break;
                }
            });
        }

        switch ($request->get('sort')) {
            case 'price_asc':
                $query->withMin(['variants as min_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                      ->orderBy('min_variant_price');
                break;

            case 'price_desc':
                $query->withMax(['variants as max_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                      ->orderByDesc('max_variant_price');
                break;

            case 'best_seller':
                $query->orderByDesc('sold_count')->latest();
                break;

            case 'newest':
                $query->latest();
                break;

            default:
                $query->orderByDesc('is_featured')
                      ->orderByDesc('sold_count')
                      ->latest();
                break;
        }

        $products = $query->paginate(40)->withQueryString();
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $brands = Brand::whereHas('products', fn($q) => $q->where('status', 1))->orderBy('name')->get();

        return view('frontend.products.featured', compact('products', 'categories', 'brands'));
    }
}