<div class="overflow-x-auto">
    <table class="w-full text-white">
        <thead class="bg-gray-800/70 text-gray-300 text-sm uppercase">
            <tr>
                <th class="p-3">Mã đơn</th>
                <th class="p-3">Khách</th>
                <th class="p-3">SĐT</th>
                <th class="p-3">Số SP</th>
                <th class="p-3">Tổng tiền</th>
                <th class="p-3">Thanh toán</th>
                <th class="p-3">Trạng thái</th>
                <th class="p-3">Ngày tạo</th>
                <th class="p-3">Hành động</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $order)
                <tr class="border-b border-gray-700 hover:bg-gray-800/40 transition">
                    <td class="p-3 font-semibold">{{ $order->order_number }}</td>
                    <td class="p-3">{{ $order->shipping_name ?? ($order->user->name ?? '-') }}</td>
                    <td class="p-3">{{ $order->shipping_phone ?? '-' }}</td>
                    <td class="p-3 text-center">{{ $order->items_count }}</td>
                    <td class="p-3">{{ number_format($order->grand_total, 0, ',', '.') }} ₫</td>

                    <td class="p-3">
                        @if($order->payment_status === 'paid')
                            <span class="px-2 py-1 text-xs bg-green-500/20 text-green-300 rounded">Đã thanh toán</span>
                        @elseif($order->payment_status === 'refunded')
                            <span class="px-2 py-1 text-xs bg-cyan-500/20 text-cyan-300 rounded">Đã hoàn tiền</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-yellow-500/20 text-yellow-300 rounded">Chưa thanh toán</span>
                        @endif
                    </td>

                    <td class="p-3">
                        @php
                            $statusMap = [
                                'pending' => ['Chờ xử lý', 'bg-yellow-500/20 text-yellow-300'],
                                'confirmed' => ['Đã xác nhận', 'bg-blue-500/20 text-blue-300'],
                                'shipping' => ['Đang giao', 'bg-purple-500/20 text-purple-300'],
                                'completed' => ['Hoàn thành', 'bg-green-500/20 text-green-300'],
                                'cancelled' => ['Đã huỷ', 'bg-red-500/20 text-red-300'],
                                'refunded' => ['Hoàn tiền', 'bg-cyan-500/20 text-cyan-300'],
                            ];
                            $status = $statusMap[$order->status] ?? [$order->status, 'bg-gray-500/20 text-gray-300'];
                        @endphp

                        <span class="px-2 py-1 text-xs rounded {{ $status[1] }}">
                            {{ $status[0] }}
                        </span>
                    </td>

                    <td class="p-3">{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>

                    <td class="p-3">
                        <a href="{{ route('orders.show', $order->id) }}" class="text-cyan-300 hover:underline">
                            Xem
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="p-6 text-center text-gray-400">
                        Không có đơn hàng nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>