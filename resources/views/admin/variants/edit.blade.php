@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

<h1 class="text-2xl font-bold text-white mb-6">
Sửa biến thể
</h1>

<form action="{{ route('variants.update', $variant->id) }}" method="POST"
class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

@csrf
@method('PUT')

{{-- Product Info --}}
<div class="bg-gray-800 p-4 rounded-lg">
    <h3 class="text-cyan-400 font-semibold mb-2">Sản phẩm</h3>
    <p class="text-gray-300">{{ $variant->product ? $variant->product->name : 'Sản phẩm không tồn tại' }}</p>
</div>

{{-- RAM --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">
        RAM <span class="text-red-500">*</span>
        <a href="{{ route('product-attributes.index', ['type' => 'ram']) }}"
           class="text-xs text-cyan-400 hover:text-cyan-300 underline ml-2">
           Quản lý
        </a>
    </label>
    <select name="ram"
        class="w-full bg-gray-800 text-white border border-gray-700
                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        @foreach($rams as $ram)
            <option value="{{ $ram }}" {{ $variant->ram == $ram ? 'selected' : '' }}>
                {{ $ram }}
            </option>
        @endforeach
    </select>
    @error('ram')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Storage --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">
        Bộ nhớ <span class="text-red-500">*</span>
        <a href="{{ route('product-attributes.index', ['type' => 'storage']) }}"
           class="text-xs text-cyan-400 hover:text-cyan-300 underline ml-2">
           Quản lý
        </a>
    </label>
    <select name="storage"
        class="w-full bg-gray-800 text-white border border-gray-700
                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        @foreach($storages as $storage)
            <option value="{{ $storage }}" {{ $variant->storage == $storage ? 'selected' : '' }}>
                {{ $storage }}
            </option>
        @endforeach
    </select>
    @error('storage')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Color --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">
        Màu sắc <span class="text-red-500">*</span>
        <a href="{{ route('product-attributes.index', ['type' => 'color']) }}"
           class="text-xs text-cyan-400 hover:text-cyan-300 underline ml-2">
           Quản lý
        </a>
    </label>
    <select name="color"
        class="w-full bg-gray-800 text-white border border-gray-700
                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        @foreach($colors as $color)
            <option value="{{ $color }}" {{ $variant->color == $color ? 'selected' : '' }}>
                {{ $color }}
            </option>
        @endforeach
    </select>
    @error('color')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Price --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">
        Giá
    </label>
    <input type="number"
        name="price"
        step="0.01"
        value="{{ old('price', $variant->price) }}"
        class="w-full bg-gray-800 text-white border border-gray-700
                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
    @error('price')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Stock --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">
        Tồn kho
    </label>
    <input type="number"
        name="stock"
        value="{{ old('stock', $variant->stock) }}"
        class="w-full bg-gray-800 text-white border border-gray-700
                  rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
    @error('stock')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Image --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">
        Hình ảnh
    </label>

    @if($variant->image)
        <div class="mb-4">
            <img src="{{ asset('storage/' . $variant->image) }}"
                 alt="{{ $variant->color }}"
                 class="w-32 h-32 object-cover rounded-lg border border-gray-700">
        </div>
    @endif

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

{{-- Buttons --}}
<div class="flex justify-end gap-4">
    <a href="{{ route('variants.index') }}"
       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
        Hủy
    </a>

    <button type="submit"
            class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
        Cập nhật biến thể
    </button>
</div>

</form>

</div>

@endsection