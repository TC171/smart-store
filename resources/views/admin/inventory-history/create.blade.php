@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Cập nhật kho hàng</h1>
        <a href="{{ route('admin.inventory-history.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Quay lại
        </a>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.inventory-history.store') }}" method="POST"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf

        {{-- Sản phẩm --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Sản phẩm <span class="text-red-500">*</span>
            </label>
            <select name="product_variant_id" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 @error('product_variant_id') border-red-500 @enderror" required>
                <option value="">Chọn sản phẩm</option>
                @foreach($productVariants as $variant)
                    <option value="{{ $variant->id }}" {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                        {{ $variant->product ? $variant->product->name : 'Sản phẩm không tồn tại' }} - {{ $variant->sku }} (Kho: {{ $variant->stock }})
                    </option>
                @endforeach
            </select>
            @error('product_variant_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Loại cập nhật --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Loại cập nhật <span class="text-red-500">*</span>
            </label>
            <select name="type" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 @error('type') border-red-500 @enderror" required>
                <option value="">Chọn loại</option>
                <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Nhập kho</option>
                <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Xuất kho</option>
                <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Điều chỉnh tồn kho</option>
            </select>
            @error('type')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Số lượng --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Số lượng <span class="text-red-500">*</span>
            </label>
            <input type="number" name="quantity" value="{{ old('quantity') }}" min="1"
                placeholder="Nhập số lượng"
                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 @error('quantity') border-red-500 @enderror" required>
            @error('quantity')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ghi chú --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Ghi chú
            </label>
            <textarea name="notes" rows="3" placeholder="Nhập ghi chú (tùy chọn)"
                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
            @error('notes')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.inventory-history.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Cập nhật kho
            </button>
        </div>

    </form>

</div>

@endsection