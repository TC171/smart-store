@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">🚴 Quản lý Shipper</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.shippers.assign') }}"
               class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                📦 Phân công đơn hàng
            </a>
            <a href="{{ route('admin.shippers.deliveries') }}"
               class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                🗺️ Theo dõi giao hàng
            </a>
            <a href="{{ route('admin.shippers.create') }}"
               class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                ➕ Thêm Shipper
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="mb-6 bg-gray-900 p-4 rounded-xl">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Tìm theo tên, email, SĐT..." value="{{ request('search') }}"
                class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 w-72">
            <select name="status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="">-- Tất cả --</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã khóa</option>
            </select>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Lọc</button>
            <a href="{{ route('admin.shippers.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Xóa lọc</a>
        </form>
    </div>

    @if($shippers->count() > 0)
    <div class="overflow-x-auto bg-gray-900 rounded-xl shadow-lg">
        <table class="w-full">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Shipper</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Email / SĐT</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">🚴 Đang giao</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">✅ Đã giao</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">❌ Thất bại</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Tổng</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shippers as $shipper)
                <tr class="border-b border-gray-800 hover:bg-gray-800/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($shipper->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white font-medium">{{ $shipper->name }}</div>
                                <div class="text-gray-500 text-xs">ID #{{ $shipper->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-300 text-sm">{{ $shipper->email }}</div>
                        <div class="text-gray-400 text-xs">{{ $shipper->phone ?? '–' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-indigo-400 font-bold text-lg">{{ $shipper->shipping_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-green-400 font-bold text-lg">{{ $shipper->completed_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-red-400 font-bold text-lg">{{ $shipper->failed_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-white font-bold">{{ $shipper->assigned_orders_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($shipper->status)
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">Hoạt động</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400">Đã khóa</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-3">
                            <a href="{{ route('admin.shippers.edit', $shipper) }}" class="text-cyan-400 hover:text-cyan-300 text-sm font-medium">Sửa</a>
                            <form action="{{ route('admin.shippers.destroy', $shipper) }}" method="POST"
                                  onsubmit="return confirm('Xác nhận xóa shipper {{ $shipper->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $shippers->links('pagination::tailwind') }}</div>
    @else
    <div class="text-center py-16 bg-gray-900 rounded-xl">
        <div class="text-6xl mb-4">🚴</div>
        <p class="text-gray-400 text-lg">Chưa có shipper nào</p>
        <a href="{{ route('admin.shippers.create') }}" class="mt-4 inline-block bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2 rounded-lg">Thêm Shipper</a>
    </div>
    @endif

</div>
@endsection
