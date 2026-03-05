@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold text-white mb-6">
        Thêm danh mục
    </h1>

    <form action="{{ route('categories.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf

        <!-- Tên danh mục -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên danh mục
            </label>

            <input type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:outline-none 
                       focus:ring-2 focus:ring-cyan-500">

            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Danh mục cha -->
        <!-- <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Danh mục cha
            </label>

            <select name="parent_id"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">

                <option value="">-- Danh mục gốc --</option>

                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">
                    {{ $cat->name }}
                </option>
                @endforeach

            </select>
        </div> -->

        <!-- Ảnh danh mục -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Hình ảnh
            </label>

            <input type="file"
                name="image"
                class="w-full text-gray-300 file:bg-cyan-500 
                       file:text-black file:px-4 file:py-2 
                       file:rounded-lg file:border-0">
        </div>

        <!-- Trạng thái -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Trạng thái
            </label>

            <select name="status"
                class="w-full bg-gray-800 text-white border border-gray-700 
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">

                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>

            </select>
        </div>

        <!-- Danh mục nổi bật -->
        <div class="flex items-center gap-2">
            <input type="checkbox"
                name="is_featured"
                value="1"
                class="w-4 h-4 text-cyan-500 bg-gray-800 border-gray-600 rounded">

            <label class="text-gray-300">
                Danh mục nổi bật
            </label>
        </div>

        <!-- Button -->
        <div class="flex justify-end">
<button type="submit"
                class="bg-cyan-500 hover:bg-cyan-600 
                       text-black px-6 py-2 rounded-lg font-semibold">
                Lưu danh mục
            </button>
        </div>

    </form>

</div>
@endsection