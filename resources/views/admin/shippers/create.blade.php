@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.shippers.index') }}" class="text-gray-400 hover:text-white transition">← Danh sách shipper</a>
        <span class="text-gray-600">/</span>
        <h1 class="text-2xl font-bold text-white">Thêm Shipper mới</h1>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-gray-900 rounded-xl p-6">
        <form action="{{ route('admin.shippers.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Họ tên <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Mật khẩu <span class="text-red-400">*</span></label>
                <input type="password" name="password" required minlength="6"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Xác nhận mật khẩu <span class="text-red-400">*</span></label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Trạng thái</label>
                <select name="status" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="1" selected>Hoạt động</option>
                    <option value="0">Khóa</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2 rounded-lg font-medium transition">
                    Tạo tài khoản
                </button>
                <a href="{{ route('admin.shippers.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
