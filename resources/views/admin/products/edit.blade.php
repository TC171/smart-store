@extends('admin.layouts.app')

@section('content')

<div class="p-6">
    <h1 class="text-2xl font-bold text-white mb-6">
        Sửa sản phẩm
    </h1>

    <form action="{{ route('products.update', $product->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf
        @method('PUT')

        {{-- TÊN SẢN PHẨM --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên sản phẩm
            </label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $product->name) }}"
                   class="w-full bg-gray-800 text-white border border-gray-700 
                          rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">

            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- DANH MỤC + THƯƠNG HIỆU --}}
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Danh mục
                </label>
                <select name="category_id"
                        class="w-full bg-gray-800 text-white border border-gray-700 
                               rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">

                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Thương hiệu
                </label>
                <select name="brand_id"
                        class="w-full bg-gray-800 text-white border border-gray-700 
                               rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">

                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}"
                            {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>

        {{-- GIÁ --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Giá
            </label>
            <input type="number"
                   name="price"
                   value="{{ old('price', $product->variants->first()->price ?? 0) }}"
                   class="w-full bg-gray-800 text-white border border-gray-700 
                          rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">

            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ẢNH HIỆN TẠI --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Hình ảnh hiện tại
            </label>

            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                     class="w-32 h-32 object-cover rounded-lg border mb-4">
            @else
                <p class="text-gray-400 mb-4">Chưa có ảnh</p>
            @endif

            <input type="file"
                   name="image"
                   class="w-full text-gray-300 file:bg-cyan-500 
                          file:text-black file:px-4 file:py-2 
                          file:rounded-lg file:border-0">
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-4">

            <a href="{{ route('products.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 
                           text-black px-6 py-2 rounded-lg font-semibold">
                Cập nhật sản phẩm
            </button>
        </div>

    </form>
</div>

@endsection