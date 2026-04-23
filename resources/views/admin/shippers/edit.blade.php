@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.shippers.index') }}" class="text-gray-400 hover:text-white transition">← Danh sách shipper</a>
        <span class="text-gray-600">/</span>
        <h1 class="text-2xl font-bold text-white">Chỉnh sửa Shipper</h1>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-gray-900 rounded-xl p-6">
        <form action="{{ route('admin.shippers.update', $shipper) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Họ tên <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $shipper->name) }}" required
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $shipper->email) }}" required
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $shipper->phone) }}"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>

            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-400 text-sm mb-3">🔒 Đổi mật khẩu (để trống nếu không muốn thay đổi)</p>
                <div class="space-y-3">
                    <input type="password" name="password" placeholder="Mật khẩu mới (ít nhất 6 ký tự)"
                        class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu mới"
                        class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Trạng thái</label>
                <select name="status" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="1" {{ $shipper->status ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ !$shipper->status ? 'selected' : '' }}>Khóa tài khoản</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2 rounded-lg font-medium transition">
                    Lưu thay đổi
                </button>
                <a href="{{ route('admin.shippers.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
