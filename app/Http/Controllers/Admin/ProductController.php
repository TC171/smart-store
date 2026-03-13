<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        // FILTER CATEGORY
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        // FILTER BRAND
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // FILTER STATUS
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // SORT GIÁ (dùng subquery)
        if ($request->sort_price) {

            $query->orderBy(
                DB::raw("(SELECT COALESCE(sale_price, price)
                     FROM product_variants
                     WHERE product_variants.product_id = products.id
                     ORDER BY id ASC
                     LIMIT 1)"),
                $request->sort_price
            );
        } else {
            $query->latest();
        }

        $products = $query->paginate(10);

        if ($request->ajax()) {
            $categories = Category::all();
            $brands = Brand::all();

            return view('admin.products.partials.table', compact(
                'products',
                'categories',
                'brands'
            ))->render();
        }

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = \App\Models\ProductAttribute::where('type', 'color')->orderBy('sort_order')->get();
        $storages = \App\Models\ProductAttribute::where('type', 'storage')->orderBy('sort_order')->get();
        $rams = \App\Models\ProductAttribute::where('type', 'ram')->orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories', 'brands', 'colors', 'storages', 'rams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'brand_id' => 'required',
            'colors' => 'nullable|string',
            'storages' => 'nullable|string',
            'rams' => 'nullable|string',
            'default_price' => 'nullable|numeric|min:0',
            'default_stock' => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($request->name);

        // 🔥 kiểm tra sản phẩm đã tồn tại chưa
        if (Product::where('slug', $slug)->exists()) {
            return back()
                ->withErrors(['name' => 'Sản phẩm này đã tồn tại'])
                ->withInput();
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'thumbnail' => $imagePath ?? null,
            'status' => 1
        ]);

        // Tạo variants nếu có input
        if ($request->colors && $request->storages && $request->rams) {
            $colors = $request->colors;
            $storages = $request->storages;
            $rams = $request->rams;

            foreach ($colors as $color) {
                foreach ($storages as $storage) {
                    foreach ($rams as $ram) {
                        $baseSku = $this->generateSku($product, $color, $storage, $ram);
                        $sku = $baseSku;
                        $counter = 1;

                        // Đảm bảo SKU unique
                        while (\App\Models\ProductVariant::where('sku', $sku)->exists()) {
                            $sku = $baseSku . '-' . $counter;
                            $counter++;
                        }

                        \App\Models\ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $sku,
                            'color' => $color,
                            'storage' => $storage,
                            'ram' => $ram,
                            'price' => $request->default_price ?? 0,
                            'stock' => $request->default_stock ?? 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $colors = \App\Models\ProductAttribute::where('type', 'color')->orderBy('sort_order')->get();
        $storages = \App\Models\ProductAttribute::where('type', 'storage')->orderBy('sort_order')->get();
        $rams = \App\Models\ProductAttribute::where('type', 'ram')->orderBy('sort_order')->get();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'brands',
            'colors',
            'storages',
            'rams'
        ));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('variants')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        // Cập nhật slug nếu đổi tên
        $slug = Str::slug($request->name);

        if (
            Product::where('slug', $slug)
            ->where('id', '!=', $product->id)
            ->exists()
        ) {
            return back()
                ->withErrors(['name' => 'Sản phẩm này đã tồn tại'])
                ->withInput();
        }

        // Upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->thumbnail = $imagePath;
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'status' => $request->status ?? $product->status,
        ]);

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted');
    }


    public function import(Request $request)
    {
        Excel::import(new ProductsImport, $request->file('file'));

        return back()->with('success', 'Import thành công');
    }
    public function show($id)
    {
        $product = Product::with([
            'category',
            'brand',
            'variants'
        ])->findOrFail($id);

        $minPrice = $product->variants->min('price');
        $maxPrice = $product->variants->max('price');
        $totalStock = $product->variants->sum('stock');

        return view('admin.products.show', compact(
            'product',
            'minPrice',
            'maxPrice',
            'totalStock'
        ));
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['status' => !$product->status]);

        return response()->json([
            'success' => true,
            'status' => $product->status,
            'message' => $product->status ? 'Đã kích hoạt sản phẩm' : 'Đã ngừng kinh doanh sản phẩm'
        ]);
    }

    private function generateSku($product, $color, $storage, $ram)
    {
        $productCode = strtoupper(substr($product->slug, 0, 5));
        $colorCode = strtoupper(substr($color, 0, 3));
        $storageCode = str_replace(['GB', 'TB'], '', $storage);
        $ramCode = str_replace('GB', '', $ram);

        return $productCode . '-' . $colorCode . '-' . $storageCode . '-' . $ramCode;
    }
}
