@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-white mb-6">
        Thêm Banner
    </h1>

    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf

        {{-- Tiêu đề --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tiêu đề <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('title')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Hình ảnh --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Hình ảnh <span class="text-red-500">*</span>
            </label>
            <input type="file" name="image" accept="image/*"
                class="w-full text-gray-300 file:bg-cyan-500 
                       file:text-black file:px-4 file:py-2 
                       file:rounded-lg file:border-0">
            <p class="text-gray-400 text-sm mt-1">
                Định dạng: JPEG, PNG, JPG, GIF, WebP (Tối đa 5MB)
            </p>
            @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Link --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Liên kết
            </label>
            <input type="url" name="link" value="{{ old('link') }}"
                placeholder="https://example.com"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('link')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vị trí --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Vị trí <span class="text-red-500">*</span>
            </label>
            <select name="position"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="">-- Chọn vị trí --</option>
                @foreach($positions as $pos)
                <option value="{{ $pos }}" {{ old('position') == $pos ? 'selected' : '' }}>
                    {{ ucfirst($pos) }}
                </option>
                @endforeach
            </select>
            @error('position')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Sắp xếp --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Thứ tự sắp xếp
            </label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
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

        {{-- Buttons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('banners.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Thêm Banner
            </button>
        </div>

    </form>

</div>

@endsection
