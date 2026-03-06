@extends('admin.layouts.app')

@section('content')
<div class="p-6 text-white">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Chi tiết đơn hàng #{{ $order->order_number }}</h1>
        <a href="{{ route('orders.index') }}" class="text-cyan-300 hover:underline">← Quay lại</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-gray-900/70 rounded-xl p-5 border border-cyan-500/10">
            <h2 class="text-lg font-semibold mb-4">Sản phẩm trong đơn</h2>

            <div class="space-y-3">
                @forelse($order->items as $item)
                    <div class="border border-white/5 rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <div class="font-semibold">{{ $item->product_name ?? ($item->product->name ?? '-') }}</div>
                            <div class="text-sm text-gray-400">SKU: {{ $item->sku ?? '-' }}</div>
                        </div>

                        <div class="text-right">
                            <div>Số lượng: {{ $item->quantity }}</div>
                            <div>{{ number_format($item->price, 0, ',', '.') }} ₫</div>
                            <div class="text-cyan-300 font-semibold">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</div>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-400">Không có sản phẩm</div>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-900/70 rounded-xl p-5 border border-cyan-500/10">
            <h2 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h2>

            <div class="space-y-2 text-sm">
                <p><span class="text-gray-400">Khách hàng:</span> {{ $order->shipping_name ?? ($order->user->name ?? '-') }}</p>
                <p><span class="text-gray-400">SĐT:</span> {{ $order->shipping_phone ?? '-' }}</p>
                <p><span class="text-gray-400">Địa chỉ:</span> {{ $order->shipping_address ?? '-' }}</p>
                <p><span class="text-gray-400">Thành phố:</span> {{ $order->shipping_city ?? '-' }}</p>
                <p><span class="text-gray-400">Quận/Huyện:</span> {{ $order->shipping_district ?? '-' }}</p>
                <p><span class="text-gray-400">Quốc gia:</span> {{ $order->shipping_country ?? '-' }}</p>

                <hr class="border-white/10 my-3">

                <p><span class="text-gray-400">Tạm tính:</span> {{ number_format($order->total_amount, 0, ',', '.') }} ₫</p>
                <p><span class="text-gray-400">Phí ship:</span> {{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</p>
                <p><span class="text-gray-400">Giảm giá:</span> {{ number_format($order->discount_amount, 0, ',', '.') }} ₫</p>
                <p><span class="text-gray-400">Thuế:</span> {{ number_format($order->tax_amount, 0, ',', '.') }} ₫</p>
                <p class="font-bold text-cyan-300">
                    Tổng cộng: {{ number_format($order->grand_total, 0, ',', '.') }} ₫
                </p>

                <hr class="border-white/10 my-3">

                <form method="POST" action="{{ route('orders.update', $order->id) }}">
                    @csrf
                    @method('PUT')

                    <label class="block mb-2 text-gray-300">Cập nhật trạng thái</label>
                    <select name="status"
                        class="w-full bg-black/30 border border-cyan-500/20 text-white rounded-lg px-3 py-2 mb-3">
                        <option value="pending" {{ $order->status=='pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="confirmed" {{ $order->status=='confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="shipping" {{ $order->status=='shipping' ? 'selected' : '' }}>Đang giao</option>
                        <option value="completed" {{ $order->status=='completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ $order->status=='cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                        <option value="refunded" {{ $order->status=='refunded' ? 'selected' : '' }}>Hoàn tiền</option>
                    </select>

                    <button type="submit"
                        class="w-full px-4 py-2 rounded-lg bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 hover:bg-cyan-500/30 transition">
                        Cập nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection