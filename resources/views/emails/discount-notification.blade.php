<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ưu đãi từ Smart Store</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f4f7; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -1px; }
        .header span { color: #f97316; }
        .content { padding: 40px 30px; text-align: center; }
        .greeting { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: #1e293b; }
        .message { font-size: 16px; line-height: 1.6; color: #64748b; margin-bottom: 30px; }
        .coupon-box { 
            background: #fff7ed; 
            border: 2px dashed #fb923c; 
            border-radius: 12px; 
            padding: 30px; 
            margin: 20px 0; 
            position: relative;
        }
        .coupon-label { font-size: 12px; font-weight: 800; color: #f97316; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .coupon-code { font-size: 36px; font-weight: 900; color: #1e293b; letter-spacing: 4px; margin-bottom: 5px; }
        .coupon-value { font-size: 18px; font-weight: 700; color: #f97316; }
        .cta-button { 
            display: inline-block; 
            background: #f97316; 
            color: #ffffff; 
            text-decoration: none; 
            padding: 16px 40px; 
            border-radius: 12px; 
            font-weight: 700; 
            font-size: 16px; 
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }
        .footer { background: #f8fafc; padding: 30px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .footer a { color: #f97316; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Smart<span>Store</span></h1>
            <p style="margin-top: 10px; opacity: 0.8;">Công nghệ dẫn đầu - Trải nghiệm xứng tầm</p>
        </div>
        
        <div class="content">
            <div class="greeting">Chào bạn thân yêu,</div>
            <div class="message">
                Chúng tôi biết bạn luôn mong chờ những sản phẩm công nghệ mới nhất. Vì vậy, Smart Store xin gửi tới bạn một món quà đặc biệt để hiện thực hóa đam mê của mình.
            </div>

            <div class="coupon-box">
                <div class="coupon-label">Mã Giảm Giá Của Bạn</div>
                <div class="coupon-code">{{ $coupon->code }}</div>
                <div class="coupon-value">Ưu đãi giảm ngay @if($coupon->type == 'percent') {{ $coupon->value }}% @else {{ number_format($coupon->value) }}đ @endif</div>
            </div>

            <p style="font-size: 14px; color: #94a3b8; font-style: italic;">* Áp dụng cho mọi đơn hàng tại website Smart Store</p>

            <a href="{{ url('/') }}" class="cta-button">SỬ DỤNG NGAY</a>
        </div>

        <div class="footer">
            Bạn nhận được email này vì đã đăng ký nhận ưu đãi tại Smart Store.<br>
            Nếu muốn dừng nhận tin, vui lòng <a href="#">Hủy đăng ký</a>.<br><br>
            <strong>Địa chỉ:</strong> Cao đẳng FPT, Trịnh Văn Bô, Nam Từ Liêm, Hà Nội<br>
            <strong>Hotline:</strong> 1900 1234
        </div>
    </div>
</body>
</html>
