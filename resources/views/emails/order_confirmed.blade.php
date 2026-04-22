<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f7f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 15px rgba(0,0,0,0.06); margin: 0 auto; min-width: 600px;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%); padding: 35px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">SMART STORE</h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 15px;">Thư Xác Nhận Đơn Hàng Mới</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 35px;">
                            <h2 style="margin: 0 0 15px 0; font-size: 22px; color: #222222; font-weight: 700;">Xin chào {{ $order->shipping_name }},</h2>
                            <p style="margin: 0 0 25px 0; font-size: 15px; line-height: 1.6; color: #555555;">
                                Tuyệt vời! Bạn vừa đặt thành công đơn hàng tại <strong>Smart Store</strong>. <br>Hệ thống đã nhận được yêu cầu và chuẩn bị bàn giao cho bưu cục.
                            </p>

                            <!-- Order Info Box -->
                            <div style="background-color: #f8fafc; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0; padding: 25px;">
                                <h3 style="margin: 0 0 15px 0; font-size: 15px; color: #e53935; border-bottom: 2px solid #ffe5e5; padding-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Thông Tin Đơn Hàng :#{{ $order->order_number }}</h3>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size: 14px; line-height: 1.8;">
                                    <tr>
                                        <td width="110" style="color: #888888;">Người nhận:</td>
                                        <td style="font-weight: 600; color: #1a202c;">{{ $order->shipping_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #888888;">Điện thoại:</td>
                                        <td style="font-weight: 500; color: #2d3748;">{{ $order->shipping_phone }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #888888;" valign="top">Giao tới:</td>
                                        <td style="color: #2d3748; line-height: 1.5;">{{ $order->shipping_address }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #888888;" valign="top">Trạng thái:</td>
                                        <td style="color: #2d3748; line-height: 1.5;"><span style="color: #e53935; font-weight: bold;">Chờ xác nhận & Lấy hàng</span></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Items Table -->
                            <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #2d3748; border-left: 4px solid #e53935; padding-left: 10px;">Chi tiết Sản phẩm</h3>
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
                                                <strong style="color: #1e293b; font-size: 15px;">{{ $item->product_name }}</strong>
                                                @if($item->variant)
                                                    <div style="font-size: 12px; color: #64748b; margin-top: 6px;">
                                                        @if($item->variant->color)Màu: <strong>{{ $item->variant->color }}</strong>@endif
                                                        @if($item->variant->storage) | Loại: <strong>{{ $item->variant->storage }}</strong>@endif
                                                        @if($item->variant->ram) | RAM: <strong>{{ $item->variant->ram }}</strong>@endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td align="center" style="border-bottom: 1px solid #e2e8f0; padding: 15px; color: #475569; font-size: 15px; font-weight: bold;">{{ $item->quantity }}</td>
                                            <td align="right" style="border-bottom: 1px solid #e2e8f0; padding: 15px; font-weight: bold; color: #1e293b; font-size: 15px;">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
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
                                    <td align="right" style="border-top: 2px solid #cbd5e1; padding-top: 16px; font-size: 16px; font-weight: bold; color: #1e293b;">TỔNG THANH TOÁN:</td>
                                    <td align="right" width="140" style="border-top: 2px solid #cbd5e1; padding-top: 16px; font-weight: 900; color: #e53935; font-size: 22px;">
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
                                Bạn có thể xem lại thông tin đơn hàng trên trang web của chúng tôi.
                            </p>
                            <a href="{{ url('/') }}" style="display: inline-block; padding: 12px 28px; background-color: #1e293b; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                Về Trang Chủ
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
