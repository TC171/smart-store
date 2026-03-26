<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();

        // Filter status
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $brands = $query->latest()->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|boolean',
        ]);

        $slug = Str::slug($request->name);

        if (Brand::where('slug', $slug)->exists()) {
            return back()
                ->withErrors(['name' => 'Thương hiệu này đã tồn tại'])
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'website' => $request->website,
            'country' => $request->country,
            'status' => $request->status,
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thêm thương hiệu thành công');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,'.$brand->id,
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|boolean',
        ]);

        $slug = Str::slug($request->name);

        if (Brand::where('slug', $slug)->where('id', '!=', $brand->id)->exists()) {
            return back()
                ->withErrors(['name' => 'Thương hiệu này đã tồn tại'])
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'website' => $request->website,
            'country' => $request->country,
            'status' => $request->status,
        ];

        if ($request->hasFile('logo')) {
            // Xóa logo cũ
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Cập nhật thương hiệu thành công');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return back()->with('success', 'Xóa thương hiệu thành công');
    }
}
