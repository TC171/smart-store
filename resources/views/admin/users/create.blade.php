@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-white mb-6">
        Thêm Tài khoản
    </h1>

    <form action="{{ route('users.store') }}" method="POST"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf

        {{-- Tên --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
                placeholder="Tên người dùng"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                placeholder="example@email.com"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mật khẩu --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Mật khẩu <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password"
                placeholder="Nhập mật khẩu"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Xác nhận mật khẩu --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Xác nhận mật khẩu <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password_confirmation"
                placeholder="Xác nhận mật khẩu"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('password_confirmation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Điện thoại --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Điện thoại
            </label>
            <input type="tel" name="phone" value="{{ old('phone') }}"
                placeholder="0123456789"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vai trò --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Vai trò <span class="text-red-500">*</span>
            </label>
            <select name="role"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="">-- Chọn vai trò --</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Nhân viên</option>
                <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Khách hàng</option>
            </select>
            @error('role')
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
                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Vô hiệu hóa</option>
            </select>
            @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('users.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Thêm Tài khoản
            </button>
        </div>

    </form>

</div>

@endsection
