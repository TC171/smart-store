<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::orderBy('sort_order');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', filter_var($request->status, FILTER_VALIDATE_BOOLEAN));
        }

        $banners = $query->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        $positions = ['header', 'footer', 'sidebar', 'popup'];
        return view('admin.banners.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url',
            'position' => 'required|in:header,footer,sidebar,popup',
            'status' => 'required|boolean',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('banners.index')
            ->with('success', 'Thêm banner thành công');
    }

    public function edit(Banner $banner)
    {
        $positions = ['header', 'footer', 'sidebar', 'popup'];
        return view('admin.banners.edit', compact('banner', 'positions'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url',
            'position' => 'required|in:header,footer,sidebar,popup',
            'status' => 'required|boolean',
        ]);

        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'position' => $request->position,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ];

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('banners.index')
            ->with('success', 'Cập nhật banner thành công');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return back()->with('success', 'Xóa banner thành công');
    }

    public function deleteImage(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
            $banner->update(['image' => null]);
        }

        return back()->with('success', 'Xóa hình ảnh thành công');
    }
}
