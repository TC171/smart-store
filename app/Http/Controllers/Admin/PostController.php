<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('category')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::where('status', 1)->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:post_categories,id',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->title) . '-' . time();
        $data['published_at'] = now();
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết thành công');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::where('status', 1)->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:post_categories,id',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->title) . '-' . $post->id;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($post->image) Storage::disk('public')->delete($post->image);
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy(Post $post)
    {
        if ($post->image) Storage::disk('public')->delete($post->image);
        $post->delete();
        return back()->with('success', 'Xóa bài viết thành công');
    }
}
