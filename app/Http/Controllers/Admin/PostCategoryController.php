<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = PostCategory::latest()->paginate(10);
        return view('admin.post-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.post-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $slug = Str::slug($request->name);

        PostCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.post-categories.index')->with('success', 'Thêm danh mục tin thành công');
    }

    public function edit(PostCategory $postCategory)
    {
        return view('admin.post-categories.edit', compact('postCategory'));
    }

    public function update(Request $request, PostCategory $postCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $postCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.post-categories.index')->with('success', 'Cập nhật danh mục tin thành công');
    }

    public function destroy(PostCategory $postCategory)
    {
        $postCategory->delete();
        return back()->with('success', 'Xóa danh mục tin thành công');
    }
}
