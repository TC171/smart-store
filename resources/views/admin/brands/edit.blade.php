@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-white mb-6">
        Sửa thương hiệu
    </h1>

    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên thương hiệu <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $brand->name) }}"
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
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('description', $brand->description) }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Website --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Website
            </label>
            <input type="url" name="website" value="{{ old('website', $brand->website) }}"
                placeholder="https://example.com"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('website')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Quốc gia --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Quốc gia
            </label>
            <input type="text" name="country" value="{{ old('country', $brand->country) }}"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('country')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Logo --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Logo
            </label>

            @if($brand->logo)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $brand->logo) }}"
                         alt="{{ $brand->name }}"
                         class="w-32 h-32 object-cover rounded-lg border border-gray-700">
                </div>
            @endif

            <input type="file" name="logo" accept="image/*"
                class="w-full text-gray-300 file:bg-cyan-500 
                       file:text-black file:px-4 file:py-2 
                       file:rounded-lg file:border-0">
            <p class="text-gray-400 text-sm mt-1">
                Định dạng: JPEG, PNG, JPG, GIF, WebP (Tối đa 2MB)
            </p>
            @error('logo')
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
                <option value="1" {{ old('status', $brand->status) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ old('status', $brand->status) == 0 ? 'selected' : '' }}>Tắt</option>
            </select>
            @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('brands.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Cập nhật thương hiệu
            </button>
        </div>

    </form>

</div>

@endsection
