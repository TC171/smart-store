<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreProductRequest;
use App\Http\Requests\Admin\Products\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::with(['category', 'brand']);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    // Form thêm sản phẩm
    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    // Lưu sản phẩm mới
    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        if (Product::where('slug', $slug)->exists()) {
            return back()->withErrors(['slug' => 'Slug đã tồn tại'])->withInput();
        }

        $imagePath = $this->uploadImage($request);

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'thumbnail' => $imagePath,
            'warranty_months' => $request->warranty_months ?? 12,
            'weight' => $request->weight,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'is_featured' => $request->is_featured ?? 0,
            'is_new' => $request->is_new ?? 0,
            'status' => $request->status ?? 1,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    // Form edit sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product);

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    // Cập nhật sản phẩm
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product);

        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            return back()->withErrors(['slug' => 'Slug đã tồn tại'])->withInput();
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($product->thumbnail);
            $product->thumbnail = $this->uploadImage($request);
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'warranty_months' => $request->warranty_months ?? 12,
            'weight' => $request->weight,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'is_featured' => $request->is_featured ?? 0,
            'is_new' => $request->is_new ?? 0,
            'status' => $request->status ?? 1,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('admin.products.show', $product->id)
            ->with('success', 'Cập nhật sản phẩm thành công');
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'variants'])->findOrFail($id);
        $this->authorize('view', $product);

        // Xử lý giá min/max an toàn
        $minPrice = $product->variants->min('price') ?? 0;
        $maxPrice = $product->variants->max('sale_price') ?? $minPrice;

        $totalStock = $product->variants->sum('stock') ?? 0;

        return view('admin.products.show', compact('product', 'minPrice', 'maxPrice', 'totalStock'));
    }

    // Xóa sản phẩm
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->deleteImage($product->thumbnail);
        $product->delete();

        return back()->with('success', 'Xóa sản phẩm thành công');
    }

    // Chuyển trạng thái active / inactive
    public function toggleStatus(Product $product)
    {
        $this->authorize('update', $product);
        $product->update(['status' => ! $product->status]);

        return response()->json([
            'success' => true,
            'status' => $product->status
        ]);
    }

    // Hàm upload ảnh
    private function uploadImage($request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');

            $publicPath = public_path('storage/products/' . basename($imagePath));
            if (!file_exists(dirname($publicPath))) mkdir(dirname($publicPath), 0755, true);
            copy(storage_path('app/public/' . $imagePath), $publicPath);
        }
        return $imagePath;
    }

    // Hàm xóa ảnh
    private function deleteImage($path)
    {
        if ($path) {
            Storage::disk('public')->delete($path);
            $publicPath = public_path('storage/' . $path);
            if (file_exists($publicPath)) unlink($publicPath);
        }
    }
}