<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — Smart Store Delivery</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand: #FF6B2C;
            --brand-dark: #e85a1f;
            --dark: #0f0f0f;
            --dark-2: #1a1a1a;
            --dark-3: #242424;
            --dark-4: #2e2e2e;
            --text: #f0f0f0;
            --text-muted: #888;
            --border: rgba(255,255,255,0.08);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        /* Left panel */
        .panel-left {
            flex: 1;
            background: var(--dark-2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,107,44,0.15) 0%, transparent 70%);
            top: -100px; left: -100px;
            border-radius: 50%;
            animation: pulse 6s ease-in-out infinite;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,107,44,0.08) 0%, transparent 70%);
            bottom: -80px; right: -80px;
            border-radius: 50%;
            animation: pulse 6s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }

        .brand-logo {
            position: relative; z-index: 1;
            text-align: center;
            margin-bottom: 60px;
        }

        .brand-logo .icon {
            width: 80px; height: 80px;
            background: var(--brand);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 36px;
            margin: 0 auto 20px;
            box-shadow: 0 20px 60px rgba(255,107,44,0.4);
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .brand-logo h1 {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
        }

        .brand-logo p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 6px;
        }

        .features {
            position: relative; z-index: 1;
            width: 100%;
            max-width: 340px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
            animation: slideIn 0.6s ease forwards;
            opacity: 0;
        }

        .feature-item:last-child { border-bottom: none; }

        .feature-item:nth-child(1) { animation-delay: 0.1s; }
        .feature-item:nth-child(2) { animation-delay: 0.2s; }
        .feature-item:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .feature-icon {
            width: 42px; height: 42px;
            background: var(--dark-3);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .feature-text strong {
            display: block;
            color: var(--text);
            font-size: 14px;
            font-weight: 500;
        }

        .feature-text span {
            color: var(--text-muted);
            font-size: 12px;
        }

        /* Right panel — form */
        .panel-right {
            width: 480px;
            background: var(--dark);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            border-left: 1px solid var(--border);
        }

        .form-heading {
            margin-bottom: 36px;
        }

        .form-heading .tag {
            display: inline-block;
            background: rgba(255,107,44,0.15);
            color: var(--brand);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 14px;
        }

        .form-heading h2 {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .form-heading p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 8px;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #f87171;
        }

        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            color: #4ade80;
        }

        /* Form fields */
        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
            pointer-events: none;
        }

        .field input {
            width: 100%;
            background: var(--dark-3);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px 14px 44px;
            font-size: 14px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            outline: none;
        }

        .field input:focus {
            border-color: var(--brand);
            background: var(--dark-4);
            box-shadow: 0 0 0 3px rgba(255,107,44,0.12);
        }

        .field input::placeholder { color: #555; }

        /* Toggle password */
        .toggle-pw {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--text); }

        /* Submit */
        .btn-submit {
            width: 100%;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.4s;
        }

        .btn-submit:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(255,107,44,0.35);
        }

        .btn-submit:hover::after { transform: translateX(100%); }
        .btn-submit:active { transform: translateY(0); }

        .form-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .form-footer a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .form-footer a:hover { opacity: 0.8; }

        @media (max-width: 900px) {
            .panel-left { display: none; }
            .panel-right { width: 100%; padding: 40px 24px; }
        }
    </style>
</head>
<body>

    {{-- Left panel --}}
    <div class="panel-left">
        <div class="brand-logo">
            <div class="icon">🚚</div>
            <h1>Smart Store</h1>
            <p>Hệ thống quản lý giao hàng</p>
        </div>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">📦</div>
                <div class="feature-text">
                    <strong>Quản lý đơn hàng</strong>
                    <span>Xem và cập nhật tiến trình giao hàng</span>
                </div>
            </div>
           
            <div class="feature-item">
                <div class="feature-icon">✅</div>
                <div class="feature-text">
                    <strong>Xác nhận giao hàng</strong>
                    <span>Hoàn tất đơn hàng chỉ với một bước</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right panel — form --}}
    <div class="panel-right">
        <div class="form-heading">
            <span class="tag">Shipper Portal</span>
            <h2>Chào mừng<br>trở lại 👋</h2>
            <p>Đăng nhập để tiếp tục quản lý đơn hàng</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('delivery.login') }}">
            @csrf

            <div class="field">
                <label>Địa chỉ Email</label>
                <div class="input-wrap">
                    <span class="input-icon">✉️</span>
                    <input type="email" name="email" placeholder="shipper@example.com"
                           value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <p style="color:#f87171;font-size:12px;margin-top:6px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label>Mật khẩu</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <button type="button" class="toggle-pw" onclick="togglePw()">👁</button>
                </div>
                @error('password')
                    <p style="color:#f87171;font-size:12px;margin-top:6px;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Đăng nhập →</button>
        </form>

        
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>