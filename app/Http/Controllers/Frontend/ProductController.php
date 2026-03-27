<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Coupon;

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

        // ✅ check đúng category (QUAN TRỌNG)
        if (!$product->category || $product->category->slug !== $categorySlug) {
            abort(404);
        }

        $this->authorize('view', $product);

        // ✅ Giá thấp nhất - cao nhất
        $minPrice = $product->variants->min(fn($v) => $v->sale_price ?? $v->price) ?? 0;
        $maxPrice = $product->variants->max(fn($v) => $v->sale_price ?? $v->price) ?? 0;

        // ✅ Tổng tồn kho
        $totalStock = $product->variants->sum('stock');

        // ✅ Sản phẩm liên quan
        $relatedProducts = Product::with('variants')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->take(5)
            ->get();

        // ✅ COUPONS
        $coupons = Coupon::where('status', 1)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->get();

        // ✅ REVIEWS
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

// fallback nếu không có category trong map
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

    public function featured()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::where('is_featured', 1)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ])
            ->paginate(20);

        return view('frontend.products.featured', compact('products'));
    }
}