<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class SearchController extends Controller
{
    private $searchAliasMap = [
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

    public function search(Request $request)
    {
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
            if (isset($this->searchAliasMap[$prefix])) {
                $expanded = $this->searchAliasMap[$prefix] . ' ' . $suffix;
                $expandedTerms[] = $expanded;
                $expandedTerms[] = Str::ascii($expanded);
                $expandedTerms[] = Str::slug($expanded);
            }
        }

        // Cũng check toàn bộ chuỗi (không có số) có phải alias không
        $trimmedQ = trim($lowerQ);
        if (isset($this->searchAliasMap[$trimmedQ])) {
            $expandedTerms[] = $this->searchAliasMap[$trimmedQ];
        }

        $expandedTerms = array_unique(array_filter($expandedTerms));

        // Tìm theo tên/slug sản phẩm + tên/slug danh mục + tên/slug thương hiệu (tất cả cùng lúc)
        // Tìm sản phẩm khớp
        $products = Product::query()
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
        $categories = Category::where('status', 1)
            ->where(function ($query) use ($expandedTerms) {
                foreach ($expandedTerms as $term) {
                    $query->orWhere('name', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%");
                }
            })
            ->limit(3)->get();

        // Tìm thương hiệu khớp
        $brands = Brand::where('status', 1)
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
    }

    public function suggestions()
    {
        // Sản phẩm bán chạy / nổi bật
        $featured = Product::query()
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
        $categories = Category::where('status', 1)
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
        $brands = Brand::where('status', 1)
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
    }
}