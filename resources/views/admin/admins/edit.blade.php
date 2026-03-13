@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-white mb-6">
        Chỉnh sửa Quản trị viên/Nhân viên
    </h1>

    <form action="{{ route('admins.update', $admin) }}" method="POST"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
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
            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                placeholder="example@email.com"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Điện thoại --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Điện thoại
            </label>
            <input type="tel" name="phone" value="{{ old('phone', $admin->phone) }}"
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
                <option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                <option value="staff" {{ old('role', $admin->role) === 'staff' ? 'selected' : '' }}>Nhân viên</option>
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
                <option value="1" {{ old('status', $admin->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', $admin->status) == 0 ? 'selected' : '' }}>Vô hiệu hóa</option>
            </select>
            @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('admins.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Hủy
            </a>

            <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Cập nhật Tài khoản
            </button>
        </div>

    </form>

</div>

@endsection