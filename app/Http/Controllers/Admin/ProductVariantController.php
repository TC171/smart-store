<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with('product')->latest()->paginate(10);

        return view('admin.variants.index', compact('variants'));
    }

    public function create(Request $request)
    {
        $products = Product::all();
        $selectedProduct = null;

        if ($request->product_id) {
            $selectedProduct = Product::find($request->product_id);
        }

        // Load attributes from product_attributes table
        $colors = \App\Models\ProductAttribute::where('type', 'color')->orderBy('sort_order')->pluck('value');
        $storages = \App\Models\ProductAttribute::where('type', 'storage')->orderBy('sort_order')->pluck('value');
        $rams = \App\Models\ProductAttribute::where('type', 'ram')->orderBy('sort_order')->pluck('value');

        return view('admin.variants.create', compact('products', 'selectedProduct', 'colors', 'storages', 'rams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variants' => 'required|array|min:1',
            'variants.*.color' => 'required|string',
            'variants.*.storage' => 'required|string',
            'variants.*.ram' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $product = Product::find($request->product_id);

        foreach ($request->variants as $index => $variantData) {
            // Xử lý ảnh cho từng variant
            $imagePath = null;
            if ($request->hasFile("variants.{$index}.image")) {
                $imagePath = $request->file("variants.{$index}.image")->store('variants', 'public');
            }

            $baseSku = $this->generateSku($product, $variantData['color'], $variantData['storage'], $variantData['ram']);
            $sku = $baseSku;
            $counter = 1;

            // Đảm bảo SKU unique
            while (ProductVariant::where('sku', $sku)->exists()) {
                $sku = $baseSku.'-'.$counter;
                $counter++;
            }

            ProductVariant::create([
                'product_id' => $product->id,
                'color' => $variantData['color'],
                'storage' => $variantData['storage'],
                'ram' => $variantData['ram'],
                'price' => $variantData['price'],
                'stock' => $variantData['stock'],
                'sku' => $sku,
                'image' => $imagePath,
                'status' => 1,
            ]);
        }

        return redirect()->route('admin.products.show', $product->id)->with('success', 'Đã tạo '.count($request->variants).' biến thể');
    }

    public function destroy($id)
    {
        $variant = ProductVariant::findOrFail($id);

        $variant->delete();

        return redirect()
            ->route('admin.variants.index')
            ->with('success', 'Xóa biến thể thành công');
    }

    public function edit($id)
    {
        $variant = ProductVariant::findOrFail($id);

        // Load attributes from product_attributes table
        $colors = \App\Models\ProductAttribute::where('type', 'color')->orderBy('sort_order')->pluck('value');
        $storages = \App\Models\ProductAttribute::where('type', 'storage')->orderBy('sort_order')->pluck('value');
        $rams = \App\Models\ProductAttribute::where('type', 'ram')->orderBy('sort_order')->pluck('value');

        return view('admin.variants.edit', compact('variant', 'colors', 'storages', 'rams'));
    }

    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        $request->validate([
            'ram' => 'required|string|max:50',
            'storage' => 'required|string|max:50',
            'color' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $updateData = [
            'ram' => $request->ram,
            'storage' => $request->storage,
            'color' => $request->color,
            'price' => $request->price,
            'stock' => $request->stock,
        ];

        // Xử lý ảnh nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variants', 'public');
            $updateData['image'] = $imagePath;
        }

        $variant->update($updateData);

        return redirect()
            ->route('admin.variants.index')
            ->with('success', 'Cập nhật biến thể thành công');
    }

    private function generateSku($product, $color, $storage, $ram)
    {
        $productCode = strtoupper(substr($product->slug, 0, 5));

        $colorCode = strtoupper(substr($color, 0, 3));

        $storageCode = str_replace('GB', '', $storage);

        $ramCode = str_replace('GB', '', $ram);

        return $productCode.'-'.$colorCode.'-'.$storageCode.'-'.$ramCode;
    }
}
