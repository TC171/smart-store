@extends('admin.layouts.app')

@section('content')

<div class="p-6">
    <h1 class="text-2xl font-bold text-white mb-6">Thêm thuộc tính sản phẩm</h1>

    <form action="{{ route('product-attributes.store') }}"
          method="POST"
          class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6 max-w-md">

        @csrf

        {{-- Loại --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Loại thuộc tính
            </label>
            <select name="type"
                    class="w-full bg-gray-800 text-white border border-gray-700
                           rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                @foreach($types as $key => $label)
                    <option value="{{ $key }}"
                        {{ request('type') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Giá trị --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Giá trị
            </label>
            <input type="text"
                   name="value"
                   value="{{ old('value') }}"
                   class="w-full bg-gray-800 text-white border border-gray-700
                          rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500"
                   placeholder="Ví dụ: Đen, 128GB, 8GB">

            @error('value')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Thứ tự --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Thứ tự sắp xếp
            </label>
            <input type="number"
                   name="sort_order"
                   value="{{ old('sort_order', 0) }}"
                   class="w-full bg-gray-800 text-white border border-gray-700
                          rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        </div>

        {{-- Buttons --}}
        <div class="flex gap-4">
            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Thêm
            </button>

            <a href="{{ route('product-attributes.index', ['type' => request('type', 'color')]) }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>
        </div>

    </form>

</div>

@endsection