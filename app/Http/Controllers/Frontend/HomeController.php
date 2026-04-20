<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'banners' => $this->getBanners(),
            'featuredCategories' => $this->getFeaturedCategories(),
            'featuredProducts' => $this->getFeaturedProducts(),
            'brands' => $this->getBrands(),
            'coupons' => $this->getCoupons(),
            'newProducts' => $this->getNewProducts(),
            'categoryProducts' => $this->getCategoryProducts(),
        ]);
    }

    protected function getBanners()
    {
        return Banner::where('position', 'header')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();
    }

    protected function getFeaturedCategories()
    {
        return Category::where('is_featured', 1)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();
    }

    protected function getFeaturedProducts()
    {
        return Product::where('status', 1)
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ])
            ->withCount([
                'variants as total_stock' => fn ($q) => $q->select(DB::raw('COALESCE(SUM(stock), 0)'))
            ])
            ->orderByDesc('is_featured')
            ->orderByDesc('sold_count')
            ->latest()
            ->limit(12)
            ->get();
    }

    protected function getNewProducts()
    {
        return Product::where('status', 1)
            ->where(function ($q) {
                $q->where('is_new', 1)
                  ->orWhere('created_at', '>=', now()->subDays(7));
            })
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ])
            ->latest()
            ->limit(8)
            ->get();
    }

    protected function getCategoryProducts($limit = 8)
    {
        $categories = Category::where('is_featured', 1)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return $categories->map(function ($category) use ($limit) {
            $products = Product::where('status', 1)
                ->where('category_id', $category->id)
                ->with([
                    'category',
                    'brand',
                    'variants' => fn ($q) => $q->where('status', 1),
                ])
                ->withCount([
                    'variants as total_stock' => fn ($q) => $q->select(DB::raw('COALESCE(SUM(stock), 0)'))
                ])
                ->latest()
                ->limit($limit)
                ->get();

            return [
                'category' => $category,
                'products' => $products,
            ];
        })->filter(function ($item) {
            return $item['products']->isNotEmpty();
        })->values();
    }

    protected function getBrands()
    {
        return Brand::where('status', 1)
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    protected function getCoupons()
    {
        return Coupon::where('status', 1)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        // Alias viết tắt (sync với /api/search)
        $aliasMap = [
            'ip' => 'iphone', 'iph' => 'iphone',
            'ss' => 'samsung', 'sam' => 'samsung',
            'xm' => 'xiaomi', 'mi' => 'xiaomi',
            'hw' => 'huawei', 'opp' => 'oppo',
            'nk' => 'nokia', 'vv' => 'vivo', 'rl' => 'realme',
            'mb' => 'macbook', 'mac' => 'macbook',
            'aw' => 'apple watch', 'ap' => 'airpods', 'apd' => 'airpods',
            'tb' => 'tablet', 'mtb' => 'máy tính bảng',
            'dt' => 'điện thoại', 'lt' => 'laptop',
            'pk' => 'phụ kiện', 'sac' => 'sạc',
            'op' => 'ốp lưng', 'cap' => 'cáp', 'tn' => 'tai nghe',
            // Viết liền không dấu
            'dienthoai' => 'điện thoại', 'maytinhbang' => 'máy tính bảng',
            'phukien' => 'phụ kiện', 'oplung' => 'ốp lưng', 'tainghe' => 'tai nghe',
        ];

        $lowerQ = \Illuminate\Support\Str::lower($query);
        $ascii = \Illuminate\Support\Str::ascii($query);
        $asciiSlug = \Illuminate\Support\Str::slug($query);
        $searchTerms = [$query, $ascii];
        if ($asciiSlug) {
            $searchTerms[] = $asciiSlug;
        }

        // "lap top" → "laptop"
        $noSpaces = str_replace(' ', '', $lowerQ);
        if ($noSpaces !== $lowerQ) {
            $searchTerms[] = $noSpaces;
            $searchTerms[] = \Illuminate\Support\Str::ascii($noSpaces);
        }

        if (preg_match('/^([a-zA-Z]+)\s*(\d+.*)$/', $lowerQ, $matches)) {
            $prefix = $matches[1];
            $suffix = $matches[2];
            if (isset($aliasMap[$prefix])) {
                $expanded = $aliasMap[$prefix] . ' ' . $suffix;
                $searchTerms[] = $expanded;
                $searchTerms[] = \Illuminate\Support\Str::ascii($expanded);
                $searchTerms[] = \Illuminate\Support\Str::slug($expanded);
            }
        }

        if (isset($aliasMap[$lowerQ])) {
            $searchTerms[] = $aliasMap[$lowerQ];
        }

        $searchTerms = array_unique(array_filter($searchTerms));

        $productsQuery = Product::where('status', 1)
            ->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('name', 'like', "%{$term}%")
                      ->orWhere('slug', 'like', "%{$term}%");
                }
                $q->orWhereHas('category', function ($catQ) use ($searchTerms) {
                    $catQ->where(function ($inner) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $inner->orWhere('name', 'like', "%{$term}%")
                                  ->orWhere('slug', 'like', "%{$term}%");
                        }
                    });
                });
                $q->orWhereHas('brand', function ($brandQ) use ($searchTerms) {
                    $brandQ->where(function ($inner) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $inner->orWhere('name', 'like', "%{$term}%")
                                  ->orWhere('slug', 'like', "%{$term}%");
                        }
                    });
                });
            })
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ]);

        // Filters
        if ($request->filled('category')) {
            $productsQuery->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $productsQuery->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->filled('price_range') && $request->price_range !== 'all') {
            $productsQuery->whereHas('variants', function ($q) use ($request) {
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
                $productsQuery->withMin(['variants as min_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                      ->orderBy('min_variant_price');
                break;

            case 'price_desc':
                $productsQuery->withMax(['variants as max_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                      ->orderByDesc('max_variant_price');
                break;

            case 'best_seller':
                $productsQuery->orderByDesc('sold_count')->latest();
                break;

            case 'newest':
                $productsQuery->latest();
                break;

            default:
                break;
        }

        $products = $productsQuery->paginate(24)->withQueryString();

        $categories = Category::where('status', 1)->orderBy('name')->get();
        $brands = Brand::where('status', 1)->orderBy('name')->get();

        return view('frontend.search', compact('products', 'query', 'categories', 'brands'));
    }

    public function shop(Request $request)
    {
        $productsQuery = Product::where('status', 1)
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ]);

        // Filters
        if ($request->filled('category')) {
            $productsQuery->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $productsQuery->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->filled('price_range') && $request->price_range !== 'all') {
            $productsQuery->whereHas('variants', function ($q) use ($request) {
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
                $productsQuery->withMin(['variants as min_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                      ->orderBy('min_variant_price');
                break;

            case 'price_desc':
                $productsQuery->withMax(['variants as max_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                      ->orderByDesc('max_variant_price');
                break;

            case 'best_seller':
                $productsQuery->orderByDesc('sold_count')->latest();
                break;

            case 'newest':
                $productsQuery->latest();
                break;

            default:
                $productsQuery->latest();
                break;
        }

        $products = $productsQuery->paginate(24)->withQueryString();

        $categories = Category::where('status', 1)->orderBy('name')->get();
        $brands = Brand::where('status', 1)->orderBy('name')->get();

        return view('frontend.shop', compact('products', 'categories', 'brands'));
    }
}