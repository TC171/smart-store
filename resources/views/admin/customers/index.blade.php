@extends('admin.layouts.app')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Danh sách Khách hàng</h1>
        <a href="{{ route('customers.create') }}"
           class="bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg font-semibold">
            + Thêm Khách hàng
        </a>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Bộ lọc --}}
    <div class="mb-6 bg-gray-900 p-4 rounded-xl space-y-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div>
                <input type="text" name="search" placeholder="Tìm theo tên hoặc email..." value="{{ request('search') }}"
                    class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>

            <div>
                <select name="status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                </select>
            </div>

            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                Lọc
            </button>
        </form>
    </div>

    {{-- Bảng --}}
    @if ($customers->count() > 0)
    <div class="overflow-x-auto bg-gray-900 rounded-xl shadow-lg">
        <table class="w-full">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Tên</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Điện thoại</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Địa chỉ</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Đăng nhập cuối</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                    <td class="px-6 py-4 text-white font-medium">{{ $customer->name }}</td>

                    <td class="px-6 py-4 text-gray-300">{{ $customer->email }}</td>

                    <td class="px-6 py-4 text-gray-300">{{ $customer->phone ?? '-' }}</td>

                    <td class="px-6 py-4 text-gray-300">{{ $customer->address ?? '-' }}</td>

                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            {{ $customer->status ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $customer->status ? 'Hoạt động' : 'Vô hiệu' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-gray-300 text-sm">
                        {{ $customer->last_login_at ? $customer->last_login_at->format('d/m/Y H:i') : 'Chưa đăng nhập' }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-3">
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="text-cyan-500 hover:text-cyan-400 text-sm font-medium">
                                Sửa
                            </a>

                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                  onsubmit="return confirm('Xác nhận xoá tài khoản này?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-400 text-sm font-medium">
                                    Xoá
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-6">
        {{ $customers->links('pagination::tailwind') }}
    </div>

    @else
    <div class="text-center py-12 bg-gray-900 rounded-xl">
        <p class="text-gray-400 text-lg">Chưa có khách hàng nào</p>
    </div>
    @endif

</div>

@endsection