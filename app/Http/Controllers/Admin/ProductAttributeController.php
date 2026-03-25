<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'color');

        $attributes = ProductAttribute::where('type', $type)
            ->orderBy('sort_order')
            ->get();

        $types = ['color' => 'Màu sắc', 'storage' => 'Dung lượng', 'ram' => 'RAM'];

        return view('admin.product-attributes.index', compact('attributes', 'type', 'types'));
    }

    public function create()
    {
        $types = ['color' => 'Màu sắc', 'storage' => 'Dung lượng', 'ram' => 'RAM'];

        return view('admin.product-attributes.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:color,storage,ram',
            'value' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Kiểm tra trùng
        if (ProductAttribute::where('type', $request->type)->where('value', $request->value)->exists()) {
            return back()->withErrors(['value' => 'Giá trị này đã tồn tại cho loại này']);
        }

        ProductAttribute::create($request->only(['type', 'value', 'sort_order']));

        return redirect()->route('admin.product-attributes.index', ['type' => $request->type])
            ->with('success', 'Thêm thuộc tính thành công');
    }

    public function edit(ProductAttribute $productAttribute)
    {
        $types = ['color' => 'Màu sắc', 'storage' => 'Dung lượng', 'ram' => 'RAM'];

        return view('admin.product-attributes.edit', compact('productAttribute', 'types'));
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $request->validate([
            'type' => 'required|in:color,storage,ram',
            'value' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Kiểm tra trùng (trừ chính nó)
        if (ProductAttribute::where('type', $request->type)
            ->where('value', $request->value)
            ->where('id', '!=', $productAttribute->id)
            ->exists()) {
            return back()->withErrors(['value' => 'Giá trị này đã tồn tại cho loại này']);
        }

        $productAttribute->update($request->only(['type', 'value', 'sort_order']));

        return redirect()->route('admin.product-attributes.index', ['type' => $request->type])
            ->with('success', 'Cập nhật thuộc tính thành công');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();

        return redirect()->back()->with('success', 'Xóa thuộc tính thành công');
    }
}
