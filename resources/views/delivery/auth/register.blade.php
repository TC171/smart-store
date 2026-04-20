<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — Smart Store Delivery</title>
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
            overflow-x: hidden;
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

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }

        .brand-logo {
            position: relative; z-index: 1;
            text-align: center;
            margin-bottom: 50px;
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
        }

        .brand-logo p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 6px;
        }

        /* Steps */
        .steps {
            position: relative; z-index: 1;
            width: 100%;
            max-width: 340px;
        }

        .steps-title {
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--brand);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 20px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
            animation: slideIn 0.6s ease forwards;
            opacity: 0;
        }

        .step-item:nth-child(2) { animation-delay: 0.1s; }
        .step-item:nth-child(3) { animation-delay: 0.2s; }
        .step-item:nth-child(4) { animation-delay: 0.3s; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .step-num {
            width: 32px; height: 32px;
            background: var(--brand);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .step-text strong {
            display: block;
            color: var(--text);
            font-size: 13px;
            font-weight: 500;
            margin-top: 4px;
        }

        .step-text span {
            color: var(--text-muted);
            font-size: 12px;
        }

        /* Right panel */
        .panel-right {
            width: 500px;
            background: var(--dark);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px;
            border-left: 1px solid var(--border);
            overflow-y: auto;
        }

        .form-heading {
            margin-bottom: 30px;
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
            font-size: 30px;
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

        /* 2-col grid */
        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field-full { grid-column: 1 / -1; }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
        }

        .field input {
            width: 100%;
            background: var(--dark-3);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 13px 14px 13px 42px;
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

        .field-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Password strength */
        .pw-strength {
            margin-top: 8px;
            display: flex;
            gap: 4px;
        }

        .pw-bar {
            flex: 1; height: 3px;
            background: var(--dark-4);
            border-radius: 2px;
            transition: background 0.3s;
        }

        .pw-bar.active-weak { background: #ef4444; }
        .pw-bar.active-medium { background: #f59e0b; }
        .pw-bar.active-strong { background: #22c55e; }

        /* Toggle password */
        .toggle-pw {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 15px;
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
            margin-top: 4px;
        }

        .btn-submit:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(255,107,44,0.35);
        }

        .btn-submit:active { transform: translateY(0); }

        .form-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .form-footer a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover { opacity: 0.8; }

        @media (max-width: 960px) {
            .panel-left { display: none; }
            .panel-right { width: 100%; padding: 40px 24px; }
            .field-grid { grid-template-columns: 1fr; }
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

        <div class="steps">
            <div class="steps-title">Bắt đầu trong 3 bước</div>
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    <strong>Tạo tài khoản Shipper</strong>
                    <span>Điền thông tin cơ bản để đăng ký</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    <strong>Được admin kích hoạt</strong>
                    <span>Admin xét duyệt và gán đơn hàng cho bạn</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    <strong>Bắt đầu giao hàng</strong>
                    <span>Nhận đơn, cập nhật tiến trình, hoàn tất</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="panel-right">
        <div class="form-heading">
            <span class="tag">Shipper Portal</span>
            <h2>Tạo tài khoản<br>Shipper 🚀</h2>
            <p>Điền đầy đủ thông tin để đăng ký tài khoản</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('delivery.register') }}">
            @csrf

            <div class="field-grid">

                {{-- Họ tên --}}
                <div class="field field-full">
                    <label>Họ và tên</label>
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input type="text" name="name" placeholder="Nguyễn Văn A"
                               value="{{ old('name') }}" required autofocus>
                    </div>
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="field field-full">
                    <label>Địa chỉ Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉️</span>
                        <input type="email" name="email" placeholder="shipper@example.com"
                               value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mật khẩu --}}
                <div class="field">
                    <label>Mật khẩu</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" id="password"
                               placeholder="••••••••" required oninput="checkStrength(this.value)">
                        <button type="button" class="toggle-pw" onclick="togglePw('password')">👁</button>
                    </div>
                    <div class="pw-strength">
                        <div class="pw-bar" id="bar1"></div>
                        <div class="pw-bar" id="bar2"></div>
                        <div class="pw-bar" id="bar3"></div>
                    </div>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Xác nhận mật khẩu --}}
                <div class="field">
                    <label>Xác nhận mật khẩu</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔑</span>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="••••••••" required>
                        <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation')">👁</button>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn-submit">Tạo tài khoản →</button>
        </form>

        <div class="form-footer">
            Đã có tài khoản?
            <a href="{{ route('delivery.login') }}">Đăng nhập ngay</a>
        </div>
    </div>

    <script>
        function togglePw(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function checkStrength(val) {
            const bars = [
                document.getElementById('bar1'),
                document.getElementById('bar2'),
                document.getElementById('bar3'),
            ];
            bars.forEach(b => b.className = 'pw-bar');

            if (val.length === 0) return;

            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;

            const cls = score === 1 ? 'active-weak' : score === 2 ? 'active-medium' : 'active-strong';
            for (let i = 0; i < score; i++) bars[i].classList.add(cls);
        }
    </script>
</body>
</html>