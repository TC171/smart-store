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

        {{-- TRẠNG THÁI SẢN PHẨM --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Trạng thái sản phẩm
            </label>
            <select name="status"
                    class="w-full bg-gray-800 text-white border border-gray-700 
                           rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>
                    Hoạt động
                </option>
                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>
                    Ngừng kinh doanh
                </option>
            </select>
        </div>
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
    <div class="mt-10">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-cyan-400">
            Biến thể sản phẩm
        </h2>

        <a href="{{ route('variants.create', ['product_id' => $product->id]) }}"
           class="px-4 py-2 bg-cyan-500 text-black rounded hover:bg-cyan-400">
            + Thêm biến thể
        </a>
    </div>

    @if($product->variants->count() > 0)
    <div class="bg-gray-900 p-6 rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-white">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-2">SKU</th>
                        <th class="text-left py-2">Màu</th>
                        <th class="text-left py-2">Bộ nhớ</th>
                        <th class="text-left py-2">RAM</th>
                        <th class="text-left py-2">Giá</th>
                        <th class="text-left py-2">Tồn kho</th>
                        <th class="text-left py-2">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->variants as $variant)
                    <tr class="border-b border-gray-800">
                        <td class="py-2">{{ $variant->sku }}</td>
                        <td class="py-2">{{ $variant->color }}</td>
                        <td class="py-2">{{ $variant->storage }}</td>
                        <td class="py-2">{{ $variant->ram }}</td>
                        <td class="py-2">{{ number_format($variant->price) }} VND</td>
                        <td class="py-2">{{ $variant->stock }}</td>
                        <td class="py-2">
                            <a href="{{ route('variants.edit', $variant->id) }}"
                               class="text-cyan-400 hover:text-cyan-300 mr-2">Sửa</a>
                            <form action="{{ route('variants.destroy', $variant->id) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300"
                                        onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="text-gray-400">Chưa có biến thể nào.</p>
    @endif

</div>
</div>

@endsection