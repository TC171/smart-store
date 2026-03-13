@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold text-white mb-6">
        Thêm sản phẩm
    </h1>

    <form action="{{ route('products.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên sản phẩm
            </label>
            <input type="text"
                name="name"
                class="w-full bg-gray-800 text-white border border-gray-700 
                          rounded-lg px-4 py-2 focus:outline-none 
                          focus:ring-2 focus:ring-cyan-500">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Danh mục
                </label>
                <select name="category_id"
                    class="w-full bg-gray-800 text-white border border-gray-700 
                               rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

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

        {{-- BIẾN THỂ --}}
        <div class="border-t border-gray-700 pt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-cyan-400">Biến thể sản phẩm</h3>
                <a href="{{ route('product-attributes.index') }}"
                   class="text-sm text-cyan-400 hover:text-cyan-300 underline">
                    Quản lý thuộc tính
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Màu sắc
                    </label>
                    <select name="colors[]" multiple
                        class="w-full bg-gray-800 text-white border border-gray-700
                                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500"
                        size="4">
                        @foreach($colors as $color)
                            <option value="{{ $color->value }}">{{ $color->value }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Giữ Ctrl để chọn nhiều</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Bộ nhớ
                    </label>
                    <select name="storages[]" multiple
                        class="w-full bg-gray-800 text-white border border-gray-700
                                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500"
                        size="4">
                        @foreach($storages as $storage)
                            <option value="{{ $storage->value }}">{{ $storage->value }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Giữ Ctrl để chọn nhiều</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        RAM
                    </label>
                    <select name="rams[]" multiple
                        class="w-full bg-gray-800 text-white border border-gray-700
                                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500"
                        size="4">
                        @foreach($rams as $ram)
                            <option value="{{ $ram->value }}">{{ $ram->value }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Giữ Ctrl để chọn nhiều</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Giá mặc định
                    </label>
                    <input type="number"
                        name="default_price"
                        step="0.01"
                        class="w-full bg-gray-800 text-white border border-gray-700 
                                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Tồn kho mặc định
                    </label>
                    <input type="number"
                        name="default_stock"
                        class="w-full bg-gray-800 text-white border border-gray-700 
                                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-cyan-500 hover:bg-cyan-600 
                       text-black px-6 py-2 rounded-lg font-semibold">
                Lưu sản phẩm
            </button>
        </div>
    </form>

</div>
@endsection