@extends('admin.layouts.app')

@section('content')
<div class="p-6 text-white">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Chi tiết đơn: {{ $order->order_number }}</h1>
        <a href="{{ route('orders.index') }}" class="text-cyan-300 hover:underline">← Quay lại</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-gray-900 rounded-xl p-4 lg:col-span-2">
            <h2 class="font-semibold mb-3">Sản phẩm</h2>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between border-b border-gray-800 pb-2">
                        <div>
                            <div class="font-semibold">{{ $item->product_name ?? ($item->product->name ?? '-') }}</div>
                            <div class="text-xs text-gray-400">SKU: {{ $item->sku ?? '-' }}</div>
                        </div>
                        <div class="text-right">
                            <div>{{ number_format($item->price,0,',','.') }} ₫ × {{ $item->quantity }}</div>
                            <div class="text-cyan-300 font-semibold">{{ number_format($item->subtotal,0,',','.') }} ₫</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-900 rounded-xl p-4">
            <h2 class="font-semibold mb-3">Thông tin đơn</h2>

            <div class="text-sm space-y-2">
                <div><span class="text-gray-400">Khách:</span> {{ $order->shipping_name ?? ($order->user->name ?? '-') }}</div>
                <div><span class="text-gray-400">SĐT:</span> {{ $order->shipping_phone ?? '-' }}</div>
                <div><span class="text-gray-400">Địa chỉ:</span> {{ $order->shipping_address ?? '-' }}</div>

                <hr class="border-gray-800 my-2">

                <div><span class="text-gray-400">Tạm tính:</span> {{ number_format($order->total_amount,0,',','.') }} ₫</div>
                <div><span class="text-gray-400">Ship:</span> {{ number_format($order->shipping_fee,0,',','.') }} ₫</div>
                <div><span class="text-gray-400">Giảm:</span> {{ number_format($order->discount_amount,0,',','.') }} ₫</div>
                <div><span class="text-gray-400">Thuế:</span> {{ number_format($order->tax_amount,0,',','.') }} ₫</div>
                <div class="font-bold text-cyan-300">
                    Tổng: {{ number_format($order->grand_total,0,',','.') }} ₫
                </div>

                <hr class="border-gray-800 my-2">

                <form action="{{ route('orders.update', $order->id) }}" method="POST" class="flex gap-2 items-center">
                    @csrf
                    @method('PUT')
                    <select name="status" class="bg-black/30 border border-cyan-500/20 text-white rounded px-2 py-2 text-sm w-full">
                        @foreach(['pending','confirmed','shipping','completed','cancelled','refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->status===$s?'selected':'' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-black px-3 py-2 rounded-lg">
                        Cập nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection