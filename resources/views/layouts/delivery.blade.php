<!DOCTYPE html>
<html>
<head>
    <title>Shipper Panel 🚚</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            background: #212529;
            color: white;
        }

        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }

        .sidebar a:hover {
            background: #343a40;
            color: white;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .topbar {
            background: white;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

    </style>

</head>

<body>

{{-- SIDEBAR --}}

<div class="sidebar">

    <h4 class="p-3">
        🚚 Shipper
    </h4>

    <a href="{{ route('delivery.orders.index') }}">
        📦 Danh sách đơn
    </a>

    <a href="{{ route('delivery.orders.index', ['status'=>'shipping']) }}">
        🚚 Đang giao
    </a>

    <a href="{{ route('delivery.orders.index', ['status'=>'delivered']) }}">
        ✅ Đã giao
    </a>

    <a href="{{ route('delivery.orders.index', ['status'=>'failed']) }}">
        ❌ Thất bại
    </a>

    <hr>

    <form method="POST"
          action="{{ route('logout') }}">

        @csrf

        <button class="btn btn-danger w-100">
            🚪 Đăng xuất
        </button>

    </form>

</div>


{{-- CONTENT --}}

<div class="content">

    {{-- TOPBAR --}}

    <div class="topbar d-flex justify-content-between">

        <h5 class="mb-0">
            🚚 Bảng điều khiển Shipper
        </h5>

        <span>
            👤 {{ auth()->user()->name }}
        </span>

    </div>


    <div class="mt-3">

        @yield('content')

    </div>

</div>

</body>
</html>