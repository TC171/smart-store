<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h1 style="color: #d71a21;">Đơn hàng #{{ $order->order_number }} đã được tiếp nhận</h1>
    <p>Xin chào {{ $order->shipping_name }},</p>
    <p>Cảm ơn bạn đã đặt hàng tại Smart Store. Chúng tôi đã nhận được đơn hàng của bạn và sẽ xử lý trong thời gian sớm nhất.</p>

    <h2 style="margin-top: 24px;">Thông tin đơn hàng</h2>
    @php
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã huỷ',
            'refunded' => 'Đã hoàn tiền',
        ];
    @endphp

    <p>
        <strong>Số đơn hàng:</strong> {{ $order->order_number }}<br>
        <strong>Tên người nhận:</strong> {{ $order->shipping_name }}<br>
        <strong>Điện thoại:</strong> {{ $order->shipping_phone }}<br>
        <strong>Địa chỉ:</strong> {{ $order->shipping_address }}<br>
        <strong>Trạng thái:</strong> {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}<br>
    </p>

    <h3 style="margin-top: 16px;">Sản phẩm</h3>
    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse;">
        <thead>
            <tr style="background: #f5f5f5;">
                <th align="left">Sản phẩm</th>
                <th align="center">Số lượng</th>
                <th align="right">Giá</th>
                <th align="right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        @if($item->variant)
                            <div style="font-size: 12px; color: #555; margin-top: 4px;">
                                @if($item->variant->color)Màu sắc: {{ $item->variant->color }}@endif
                                @if($item->variant->storage) | Dung lượng: {{ $item->variant->storage }}@endif
                                @if($item->variant->ram) | RAM: {{ $item->variant->ram }}@endif
                            </div>
                        @endif
                    </td>
                    <td align="center">{{ $item->quantity }}</td>
                    <td align="right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td align="right">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 16px; font-weight: bold;">
        Tổng đơn hàng: {{ number_format($order->grand_total, 0, ',', '.') }}đ
    </p>

    <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng phản hồi email này hoặc liên hệ với bộ phận chăm sóc khách hàng của Smart Store.</p>

    <p>Chúc bạn một ngày tốt lành,<br>Smart Store</p>
</body>
</html>
