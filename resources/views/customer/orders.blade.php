@extends('customer.layout')

@section('customer-content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lịch sử đơn hàng</h2>
        <p class="text-gray-600 mt-1">Xem và theo dõi tất cả đơn hàng của bạn</p>
    </div>

    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Mã đơn</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Ngày đặt</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Tổng tiền</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Thanh toán</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                            {{ number_format($order->total_amount) }}₫
                        </td>
                        <td class="px-4 py-3">
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
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'shipping' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'refunded' => 'bg-orange-100 text-orange-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                            $paymentLabels = [
                                'unpaid' => 'Chưa thanh toán',
                                'paid' => 'Đã thanh toán',
                                'refunded' => 'Đã hoàn tiền'
                            ];
                            $paymentColors = [
                                'unpaid' => 'bg-red-100 text-red-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'refunded' => 'bg-orange-100 text-orange-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('customer.order.detail', $order) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có đơn hàng</h3>
            <p class="mt-1 text-sm text-gray-500">Bạn chưa đặt đơn hàng nào.</p>
        </div>
    @endif
</div>
@endsection