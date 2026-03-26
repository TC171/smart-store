@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-white mb-6">
        Thêm danh mục
    </h1>

    <form action="{{ route('admin.categories.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf

        {{-- Tên danh mục --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên danh mục <span class="text-red-500">*</span>
            </label>
            <input type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mô tả --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Mô tả
            </label>
            <textarea name="description" rows="4"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Danh mục cha --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Danh mục cha
            </label>
            <select name="parent_id"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="">-- Danh mục gốc --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
            @error('parent_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Icon --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Icon (emoji hoặc tên icon)
            </label>
            <input type="text"
                name="icon"
                value="{{ old('icon') }}"
                placeholder="📱 hoặc phone"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('icon')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ảnh danh mục --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Hình ảnh
            </label>
            <input type="file"
                name="image"
                accept="image/*"
                class="w-full text-gray-300 file:bg-cyan-500 
                       file:text-black file:px-4 file:py-2 
                       file:rounded-lg file:border-0">
            <p class="text-gray-400 text-sm mt-1">
                Định dạng: JPEG, PNG, JPG, GIF, WebP (Tối đa 2MB)
            </p>
            @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Sắp xếp --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Thứ tự sắp xếp
            </label>
            <input type="number"
                name="sort_order"
                value="{{ old('sort_order', 0) }}"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('sort_order')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Danh mục nổi bật --}}
        <div>
            <label class="flex items-center gap-3">
                <input type="checkbox"
                    name="is_featured"
                    value="1"
                    {{ old('is_featured') == 1 ? 'checked' : '' }}
                    class="w-4 h-4 text-cyan-500 bg-gray-800 border-gray-600 rounded">
                <span class="text-gray-300">Danh mục nổi bật</span>
            </label>
            @error('is_featured')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Trạng thái --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Trạng thái <span class="text-red-500">*</span>
            </label>
            <select name="status"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
            @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Meta SEO --}}
        <div class="border-t border-gray-700 pt-6">
            <h3 class="text-lg font-semibold text-cyan-400 mb-4">Meta SEO</h3>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Meta Title
                </label>
                <input type="text"
                    name="meta_title"
                    value="{{ old('meta_title') }}"
                    class="w-full bg-gray-800 text-white border border-gray-700 
                           rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                @error('meta_title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Meta Description
                </label>
                <textarea name="meta_description" rows="3"
                    class="w-full bg-gray-800 text-white border border-gray-700 
                           rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Meta Keywords
                </label>
                <textarea name="meta_keywords" rows="3"
                    class="w-full bg-gray-800 text-white border border-gray-700 
                           rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('meta_keywords') }}</textarea>
                @error('meta_keywords')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.categories.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 
                           text-black px-6 py-2 rounded-lg font-semibold">
                Thêm danh mục
            </button>
        </div>

    </form>

</div>

@endsection
