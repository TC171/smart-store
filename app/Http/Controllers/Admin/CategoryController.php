<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
{
    $categories = Category::all();

    return view('admin.categories.create', compact('categories'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        $data['slug'] = Str::slug($request->name);

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Thêm danh mục thành công');
    }

    public function edit(Category $category)
{
    $categories = Category::where('id','!=',$category->id)->get();

    return view('admin.categories.edit', compact('category','categories'));
}

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        $data['slug'] = Str::slug($request->name);

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success','Đã xóa danh mục');
    }
}