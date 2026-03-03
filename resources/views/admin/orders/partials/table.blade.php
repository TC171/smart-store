<table class="w-full text-white">
    <thead class="bg-gray-800/70 text-gray-300 text-sm uppercase">
        <tr>
            <th class="p-3 text-left">Mã đơn</th>
            <th class="p-3 text-left">Khách</th>
            <th class="p-3 text-left">SĐT</th>
            <th class="p-3 text-center">Số SP</th>
            <th class="p-3 text-right">Tổng tiền</th>
            <th class="p-3 text-center">Thanh toán</th>
            <th class="p-3 text-center">Trạng thái</th>
            <th class="p-3 text-center">Ngày tạo</th>
            <th class="p-3 text-center">Hành động</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-white/5">
        @forelse($orders as $order)
            <tr class="hover:bg-white/5 transition">
                <td class="p-3 font-semibold">{{ $order->order_number }}</td>
                <td class="p-3">{{ $order->shipping_name ?? ($order->user->name ?? '-') }}</td>
                <td class="p-3">{{ $order->shipping_phone ?? ($order->user->phone ?? '-') }}</td>

                <td class="p-3 text-center">
                    {{ $order->items?->sum('quantity') ?? 0 }}
                </td>

                <td class="p-3 text-right">
                    {{ number_format($order->grand_total, 0, ',', '.') }} ₫
                </td>

                <td class="p-3 text-center">
                    <span class="px-2 py-1 text-xs rounded border border-white/10 bg-black/20">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </td>

                <td class="p-3 text-center">
                    <span class="px-2 py-1 text-xs rounded border border-white/10 bg-black/20">
                        {{ strtoupper($order->status) }}
                    </span>
                </td>

                <td class="p-3 text-center">
                    {{ $order->created_at?->format('d/m/Y H:i') }}
                </td>

                <td class="p-3 text-center">
                    <a href="{{ route('orders.show', $order->id) }}"
                       class="text-cyan-300 hover:underline">Xem</a>
                </td>
            </tr>
        @empty
            <tr>
                <td class="p-6 text-center text-gray-400" colspan="9">
                    Không có đơn hàng nào
                </td>
            </tr>
        @endforelse
    </tbody>
</table>