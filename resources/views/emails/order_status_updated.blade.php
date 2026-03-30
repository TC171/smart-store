<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h1 style="color: #d71a21;">Cập nhật đơn hàng #{{ $order->order_number }}</h1>

    @php
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã huỷ',
            'refunded' => 'Đã hoàn tiền',
        ];
    @endphp

    @if($customMessage)
        <p>{{ $customMessage }}</p>
    @else
        <p>Xin chào {{ $order->shipping_name }},</p>
        <p>Đơn hàng của bạn vừa có cập nhật mới. Vui lòng xem chi tiết bên dưới.</p>
    @endif

    <p>
        <strong>Số đơn hàng:</strong> {{ $order->order_number }}<br>
        <strong>Trạng thái hiện tại:</strong> {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}<br>
        <strong>Họ tên:</strong> {{ $order->shipping_name }}<br>
        <strong>Điện thoại:</strong> {{ $order->shipping_phone }}<br>
        <strong>Địa chỉ:</strong> {{ $order->shipping_address }}<br>
    </p>

    <h3 style="margin-top: 24px;">Sản phẩm</h3>
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

    <p>Nếu bạn cần hỗ trợ thêm, vui lòng trả lời email này hoặc liên hệ bộ phận CSKH của Smart Store.</p>

    <p>Trân trọng,<br>Smart Store</p>
</body>
</html>
