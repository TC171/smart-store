@extends('admin.layouts.app')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Danh sách Đơn hàng</h1>
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
                <input type="text" name="search" placeholder="Tìm theo mã đơn hoặc tên khách..." value="{{ request('search') }}"
                    class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>

            <div>
                <select name="status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
            </div>

            <div>
                <select name="payment_status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">-- Tất cả trạng thái thanh toán --</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
            </div>

            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                Lọc
            </button>
        </form>
    </div>

    {{-- Bảng --}}
    @if ($orders->count() > 0)
    <div class="overflow-x-auto bg-gray-900 rounded-xl shadow-lg">
        <table class="w-full">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Mã đơn</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Tổng tiền</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Thanh toán</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Ngày</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                    <td class="px-6 py-4 text-white font-medium">{{ $order->order_number }}</td>

                    <td class="px-6 py-4 text-gray-300">{{ $order->user->name ?? 'N/A' }}</td>

                    <td class="px-6 py-4 text-gray-300">{{ $order->user->email ?? 'N/A' }}</td>

                    <td class="px-6 py-4 text-white font-semibold">
                        {{ number_format($order->total_amount) }}₫
                    </td>

                    <td class="px-6 py-4">
                        @php
                        $statusLabels = [
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'shipping' => 'Đang giao hàng',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã huỷ',
                            'refunded' => 'Đã hoàn tiền'
                        ];
                        $statusColors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-400',
                            'confirmed' => 'bg-blue-500/20 text-blue-400',
                            'shipping' => 'bg-indigo-500/20 text-indigo-400',
                            'completed' => 'bg-green-500/20 text-green-400',
                            'cancelled' => 'bg-red-500/20 text-red-400',
                            'refunded' => 'bg-orange-500/20 text-orange-400'
                        ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @php
                        $paymentLabels = [
                            'unpaid' => 'Chưa thanh toán',
                            'paid' => 'Đã thanh toán',
                            'refunded' => 'Đã hoàn tiền'
                        ];
                        $paymentColors = [
                            'unpaid' => 'bg-red-500/20 text-red-400',
                            'paid' => 'bg-green-500/20 text-green-400',
                            'refunded' => 'bg-orange-500/20 text-orange-400'
                        ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $paymentColors[$order->payment_status] ?? 'bg-gray-500/20 text-gray-400' }}">
                            {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-gray-300">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="px-6 py-4">
                        <a href="{{ route('orders.show', $order) }}"
                           class="text-cyan-500 hover:text-cyan-400 text-sm font-medium">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-6">
        {{ $orders->links('pagination::tailwind') }}
    </div>

    @else
    <div class="text-center py-12 bg-gray-900 rounded-xl">
        <p class="text-gray-400 text-lg">Chưa có đơn hàng nào</p>
    </div>
    @endif

</div>

@endsection
