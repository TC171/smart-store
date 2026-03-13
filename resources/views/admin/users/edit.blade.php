@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-white mb-6">
        Chỉnh sửa Tài khoản
    </h1>

    <form action="{{ route('users.update', $user) }}" method="POST"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tên <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
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
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
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
            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                placeholder="0123456789"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Giới tính --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Giới tính
            </label>
            <select name="gender"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="">-- Chọn giới tính --</option>
                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
            </select>
            @error('gender')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ngày sinh --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Ngày sinh
            </label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('date_of_birth')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Địa chỉ --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Địa chỉ
            </label>
            <textarea name="address" rows="3"
                placeholder="Số 123, Đường ABC, Phường XYZ"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('address', $user->address) }}</textarea>
            @error('address')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Thành phố --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Thành phố
            </label>
            <input type="text" name="city" value="{{ old('city', $user->city) }}"
                placeholder="Hà Nội"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('city')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Quốc gia --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Quốc gia
            </label>
            <input type="text" name="country" value="{{ old('country', $user->country) }}"
                placeholder="Việt Nam"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('country')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mã bưu chính --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Mã bưu chính
            </label>
            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                placeholder="100000"
                class="w-full bg-gray-800 text-white border border-gray-700
                       rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            @error('postal_code')
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
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Nhân viên</option>
                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Khách hàng</option>
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
                <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Vô hiệu hóa</option>
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
                Cập nhật Tài khoản
            </button>
        </div>

    </form>

</div>

@endsection
