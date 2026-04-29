<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng đã giao thành công</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f7f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 15px rgba(0,0,0,0.06); margin: 0 auto;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); padding: 35px 20px;">
                            <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">SMART STORE</h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 8px 0 0 0; font-size: 15px;">Đơn hàng đã được giao thành công!</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 35px;">

                            <h2 style="margin: 0 0 12px 0; font-size: 22px; color: #1e293b; font-weight: 700;">
                                Xin chào {{ $order->shipping_name }},
                            </h2>
                            <p style="margin: 0 0 25px 0; font-size: 15px; line-height: 1.7; color: #475569;">
                                Đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn đã được giao thành công. Cảm ơn bạn đã tin tưởng mua sắm tại <strong>Smart Store</strong>!
                            </p>

                            <!-- Status badge -->
                            <div style="text-align: center; margin: 30px 0; padding: 25px; background-color: #f0fdf4; border-radius: 10px; border: 2px dashed #86efac;">
                                <p style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">TRẠNG THÁI</p>
                                <span style="display: inline-block; padding: 10px 30px; background-color: #dcfce7; color: #16a34a; border: 2px solid #86efac; border-radius: 30px; font-weight: 800; font-size: 18px;">
                                    🎉 Giao hàng thành công
                                </span>
                                <p style="margin: 12px 0 0 0; font-size: 13px; color: #6b7280;">
                                    {{ now()->format('H:i, d/m/Y') }}
                                </p>
                            </div>

                            <!-- Order summary -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 25px 0; background-color: #f8fafc; border-radius: 10px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 18px 20px; border-bottom: 1px solid #e2e8f0;">
                                        <strong style="color: #1e293b; font-size: 15px;">📦 Thông tin đơn hàng</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="font-size: 14px; color: #64748b; padding: 5px 0;">Mã đơn hàng</td>
                                                <td style="font-size: 14px; color: #1e293b; font-weight: 700; text-align: right;">#{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px; color: #64748b; padding: 5px 0;">Người nhận</td>
                                                <td style="font-size: 14px; color: #1e293b; text-align: right;">{{ $order->shipping_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px; color: #64748b; padding: 5px 0;">Địa chỉ</td>
                                                <td style="font-size: 14px; color: #1e293b; text-align: right;">
                                                    {{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px; color: #64748b; padding: 5px 0;">Tổng tiền</td>
                                                <td style="font-size: 16px; color: #059669; font-weight: 800; text-align: right;">
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px; color: #64748b; padding: 5px 0;">Thanh toán</td>
                                                <td style="font-size: 14px; font-weight: 600; text-align: right; color: #16a34a;">✅ Đã thanh toán</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Review CTA -->
                            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 18px 20px; border-radius: 0 8px 8px 0; margin: 25px 0;">
                                <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 1.6;">
                                    ⭐ Hãy để lại <strong>đánh giá sản phẩm</strong> để giúp chúng tôi phục vụ bạn tốt hơn nhé!
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #64748b; line-height: 1.7;">
                                Nếu bạn có bất kỳ thắc mắc nào, hãy liên hệ với chúng tôi qua email hoặc hotline hỗ trợ.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #1e293b; padding: 25px 20px;">
                            <p style="color: rgba(255,255,255,0.7); font-size: 13px; margin: 0;">
                                © {{ date('Y') }} Smart Store. Cảm ơn bạn đã mua sắm!
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
