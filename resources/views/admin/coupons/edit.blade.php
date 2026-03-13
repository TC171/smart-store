@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-white mb-6">
        Chỉnh sửa Mã giảm giá
    </h1>

    <form action="{{ route('coupons.update', $coupon) }}" method="POST"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf
        @method('PUT')

        {{-- Mã code --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Mã code <span class="text-red-500">*</span>
            </label>
            <input type="text" name="code" value="{{ old('code', $coupon->code) }}"
                placeholder="VD: SUMMER2024"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('code')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Loại giảm giá --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-3">
                Loại giảm giá <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="type" value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'checked' : '' }}
                        class="w-4 h-4 text-cyan-500 rounded">
                    <span class="ml-2 text-gray-300">Cố định (₫)</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="type" value="percent" {{ old('type', $coupon->type) === 'percent' ? 'checked' : '' }}
                        class="w-4 h-4 text-cyan-500 rounded">
                    <span class="ml-2 text-gray-300">Phần trăm (%)</span>
                </label>
            </div>
            @error('type')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Giá trị --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Giá trị <span class="text-red-500">*</span>
            </label>
            <input type="number" name="value" value="{{ old('value', $coupon->value) }}"
                placeholder="VD: 50000 hoặc 10"
                step="0.01"
                min="0"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('value')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Đơn tối thiểu --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Đơn hàng tối thiểu <span class="text-red-500">*</span>
            </label>
            <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                placeholder="VD: 100000"
                step="1000"
                min="0"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('min_order_amount')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Giảm tối đa --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Giảm tối đa (tuỳ chọn)
            </label>
            <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}"
                placeholder="VD: 500000"
                step="1000"
                min="0"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('max_discount')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Giới hạn sử dụng --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Giới hạn sử dụng (tuỳ chọn)
            </label>
            <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}"
                placeholder="Để trống nếu không giới hạn"
                min="1"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('usage_limit')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ngày bắt đầu --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Ngày bắt đầu
            </label>
            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('starts_at')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ngày kết thúc --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Ngày kết thúc <span class="text-red-500">*</span>
            </label>
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('expires_at')
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
                <option value="1" {{ old('status', $coupon->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', $coupon->status) == 0 ? 'selected' : '' }}>Vô hiệu hóa</option>
            </select>
            @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('coupons.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Cập nhật Mã giảm giá
            </button>
        </div>

    </form>

</div>

@endsection
