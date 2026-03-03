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
                <td class="p-3">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }} ₫</td>

                <td class="p-3">
                    @php $pay = $order->payment_status ?? 'unpaid'; @endphp
                    @if($pay === 'paid')
                        <span class="px-2 py-1 text-xs bg-green-500/20 text-green-300 rounded">Đã thanh toán</span>
                    @elseif($pay === 'refunded')
                        <span class="px-2 py-1 text-xs bg-cyan-500/20 text-cyan-300 rounded">Đã hoàn tiền</span>
                    @else
                        <span class="px-2 py-1 text-xs bg-yellow-500/20 text-yellow-300 rounded">Chưa thanh toán</span>
                    @endif
                </td>

                <td class="p-3">
                    @php $st = $order->status; @endphp
                    @if($st==='pending')
                        <span class="px-2 py-1 text-xs bg-yellow-500/20 text-yellow-300 rounded">Chờ xử lý</span>
                    @elseif($st==='shipping')
                        <span class="px-2 py-1 text-xs bg-blue-500/20 text-blue-300 rounded">Đang giao</span>
                    @elseif($st==='completed')
                        <span class="px-2 py-1 text-xs bg-green-500/20 text-green-300 rounded">Hoàn thành</span>
                    @elseif($st==='cancelled')
                        <span class="px-2 py-1 text-xs bg-red-500/20 text-red-300 rounded">Bị huỷ</span>
                    @elseif($st==='refunded')
                        <span class="px-2 py-1 text-xs bg-cyan-500/20 text-cyan-300 rounded">Hoàn tiền</span>
                    @else
                        <span class="px-2 py-1 text-xs bg-gray-500/20 text-gray-300 rounded">{{ $st }}</span>
                    @endif
                </td>

                <td class="p-3 text-gray-300">{{ $order->created_at?->format('d/m/Y H:i') }}</td>

                <td class="p-3">
                    <a href="{{ route('orders.show', $order->id) }}"
                       class="text-cyan-300 hover:underline">
                        Xem
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="p-6 text-center text-gray-400">Không có đơn hàng nào</td>
            </tr>
        @endforelse
    </tbody>
</table>