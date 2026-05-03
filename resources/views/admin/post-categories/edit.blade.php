@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6"><h1 class="text-2xl font-bold text-white">Sửa danh mục: {{ $postCategory->name }}</h1></div>
    <form action="{{ route('admin.post-categories.update', $postCategory->id) }}" method="POST" class="bg-gray-900 rounded-xl p-6">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-400 mb-2">Tên danh mục</label>
            <input type="text" name="name" value="{{ $postCategory->name }}" class="w-full bg-gray-800 text-white rounded p-3" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-400 mb-2">Mô tả</label>
            <textarea name="description" class="w-full bg-gray-800 text-white rounded p-3">{{ $postCategory->description }}</textarea>
        </div>
        <div class="mb-6">
            <label class="block text-gray-400 mb-2">Trạng thái</label>
            <select name="status" class="w-full bg-gray-800 text-white rounded p-3">
                <option value="1" {{ $postCategory->status == 1 ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ $postCategory->status == 0 ? 'selected' : '' }}>Tắt</option>
            </select>
        </div>
        <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded font-bold">Cập nhật</button>
    </form>
</div>
@endsection
