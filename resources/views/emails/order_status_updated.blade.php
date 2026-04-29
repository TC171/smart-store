<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật trạng thái đơn hàng</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f7f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 15px rgba(0,0,0,0.06); margin: 0 auto; min-width: 600px;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 35px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">SMART STORE</h1>
                            <p style="color: rgba(255,255,255,0.8); margin: 8px 0 0 0; font-size: 15px;">Thông Báo Tiến Độ Giao Hàng</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 35px;">
                            @php
                                $statusLabels = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao hàng',
                                    'completed' => 'Giao hàng thành công',
                                    'cancelled' => 'Đã huỷ',
                                    'refunded' => 'Đã hoàn hàng',
                                ];
                                $statusColors = [
                                    'pending' => '#f59e0b',
                                    'confirmed' => '#3b82f6',
                                    'shipping' => '#8b5cf6',
                                    'completed' => '#10b981',
                                    'cancelled' => '#ef4444',
                                    'refunded' => '#64748b',
                                ];
                                $currentLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
                                $currentColor = $statusColors[$order->status] ?? '#334155';
                            @endphp

                            <h2 style="margin: 0 0 15px 0; font-size: 22px; color: #1e293b; font-weight: 700;">Xin chào {{ $order->shipping_name }},</h2>
                            
                            @if($customMessage)
                                <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 18px; margin-bottom: 25px; border-radius: 0 6px 6px 0;">
                                    <p style="margin: 0; font-size: 15px; line-height: 1.6; color: #92400e;">{{ $customMessage }}</p>
                                </div>
                            @else
                                <p style="margin: 0 0 25px 0; font-size: 15px; line-height: 1.6; color: #475569;">
                                    Hệ thống vừa cập nhật một trạng thái mới cho đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn.
                                </p>
                            @endif

                            <div style="text-align: center; margin: 35px 0; padding: 25px; background-color: #f8fafc; border-radius: 10px; border: 2px dashed #cbd5e1;">
                                <p style="margin: 0 0 10px 0; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">TRẠNG THÁI HIỆN TẠI</p>
                                <span style="display: inline-block; padding: 10px 24px; background-color: {{ $currentColor }}15; color: {{ $currentColor }}; border: 2px solid {{ $currentColor }}40; border-radius: 30px; font-weight: 800; font-size: 18px; text-transform: uppercase;">
                                    {{ $currentLabel }}
                                </span>
                            </div>

                            <!-- Info Split -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 35px;">
                                <tr>
                                    <td width="50%" valign="top" style="padding-right: 15px; border-right: 1px solid #e2e8f0;">
                                        <h3 style="margin: 0 0 12px 0; font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: 700;">Người nhận</h3>
                                        <p style="margin: 0; font-size: 15px; color: #1e293b; line-height: 1.6;">
                                            <strong>{{ $order->shipping_name }}</strong><br>
                                            ☎ {{ $order->shipping_phone }}
                                        </p>
                                    </td>
                                    <td width="50%" valign="top" style="padding-left: 15px;">
                                        <h3 style="margin: 0 0 12px 0; font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: 700;">Giao hàng đến</h3>
                                        <p style="margin: 0; font-size: 14px; color: #1e293b; line-height: 1.6;">
                                            {{ $order->shipping_address }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Items Table -->
                            <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #1e293b; border-left: 4px solid {{ $currentColor }}; padding-left: 10px;">Sản phẩm đã đặt</h3>
                            <table width="100%" cellpadding="12" cellspacing="0" border="0" style="border-collapse: collapse; border: 1px solid #e2e8f0; margin-bottom: 25px; border-radius: 8px; overflow: hidden;">
                                <thead>
                                    <tr style="background-color: #f1f5f9;">
                                        <th align="left" style="font-size: 13px; color: #475569; border-bottom: 2px solid #cbd5e1; padding: 12px 15px;">SẢN PHẨM</th>
                                        <th align="center" style="font-size: 13px; color: #475569; border-bottom: 2px solid #cbd5e1; padding: 12px 15px;">SL</th>
                                        <th align="right" style="font-size: 13px; color: #475569; border-bottom: 2px solid #cbd5e1; padding: 12px 15px;">THÀNH TIỀN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td align="left" style="border-bottom: 1px solid #e2e8f0; padding: 15px;">
                                                <strong style="color: #1e293b; font-size: 14px;">{{ $item->product_name }}</strong>
                                            </td>
                                            <td align="center" style="border-bottom: 1px solid #e2e8f0; padding: 15px; color: #475569; font-size: 15px; font-weight: bold;">{{ $item->quantity }}</td>
                                            <td align="right" style="border-bottom: 1px solid #e2e8f0; padding: 15px; font-weight: bold; color: #1e293b; font-size: 14px;">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Total -->
                            <table width="100%" cellpadding="8" cellspacing="0" border="0" style="margin-top: 10px; font-size: 14px;">
                                <tr>
                                    <td align="right" style="padding: 6px 10px; color: #64748b;">Tạm tính:</td>
                                    <td align="right" width="140" style="padding: 6px 10px; font-weight: 500; color: #1e293b;">{{ number_format($order->subtotal ?? $order->items->sum('subtotal'), 0, ',', '.') }}đ</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td align="right" style="padding: 6px 10px; color: #64748b;">
                                        Giảm giá 
                                        @if($order->coupon_code)
                                            (<span style="color: #e53935; font-weight: bold;">{{ $order->coupon_code }}</span>):
                                        @else
                                            :
                                        @endif
                                    </td>
                                    <td align="right" width="140" style="color: #e53935; padding: 6px 10px; font-weight: bold;">
                                        -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                                @endif
                                @php $phishipping = $order->shipping_cost ?? $order->shipping_fee ?? 0; @endphp
                                <tr>
                                    <td align="right" style="padding: 6px 10px; color: #64748b;">Phí vận chuyển:</td>
                                    <td align="right" width="140" style="padding: 6px 10px; font-weight: 500; color: #1e293b;">
                                        @if($phishipping > 0)
                                            {{ number_format($phishipping, 0, ',', '.') }}đ
                                        @else
                                            <span style="color: #10b981; font-weight: bold;">Miễn phí</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" style="border-top: 2px solid #cbd5e1; padding-top: 16px; font-size: 16px; font-weight: bold; color: #1e293b;">ĐÃ THANH TOÁN:</td>
                                    <td align="right" width="140" style="border-top: 2px solid #cbd5e1; padding-top: 16px; font-weight: 900; color: #e53935; font-size: 20px;">
                                        {{ number_format($order->grand_total, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f1f5f9; padding: 30px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 15px 0; font-size: 14px; color: #64748b;">
                                Nhấn vào liên kết bên dưới để quản lý các đơn hàng của bạn.
                            </p>
                            <a href="{{ route('customer.orders') }}" style="display: inline-block; padding: 12px 28px; background-color: {{ $currentColor }}; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                Theo Dõi Đơn Hàng
                            </a>
                            <p style="margin: 25px 0 0 0; font-size: 12px; color: #94a3b8;">
                                &copy; {{ date('Y') }} Smart Store. Đã đăng ký bản quyền.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
