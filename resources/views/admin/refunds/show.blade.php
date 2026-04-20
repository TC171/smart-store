@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.refunds.index') }}" class="text-gray-400 hover:text-orange-400 transition text-sm">
            ← Danh sách yêu cầu
        </a>
        <span class="text-gray-600">/</span>
        <span class="text-white text-sm font-medium">Yêu cầu hoàn hàng #{{ $refund->id }}</span>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Main Info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Status Badge --}}
            <div class="bg-gray-900 rounded-xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Trạng thái yêu cầu</p>
                    @php
                    $statusMap = [
                        'pending'         => ['bg-yellow-500/20 text-yellow-400 border border-yellow-500/40', '🕐 Đang chờ xét duyệt'],
                        'approved_return' => ['bg-blue-500/20   text-blue-400   border border-blue-500/40',   '📦 Chờ gửi hàng'],
                        'refunded'        => ['bg-green-500/20  text-green-400  border border-green-500/40',  '✅ Đã hoàn hàng'],
                        'rejected'        => ['bg-red-500/20    text-red-400    border border-red-500/40',    '❌ Đã từ chối'],
                    ];
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-bold {{ $statusMap[$refund->status][0] ?? '' }}">
                        {{ $statusMap[$refund->status][1] ?? $refund->status }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 text-xs">Gửi lúc</p>
                    <p class="text-white text-sm font-medium">{{ $refund->created_at->format('H:i - d/m/Y') }}</p>
                </div>
            </div>

            {{-- Order Items (Pushed to Top) --}}
            <div class="bg-gray-900 rounded-xl p-5">
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-4">Sản phẩm yêu cầu hoàn hàng</p>
                <div class="space-y-4">
                    @foreach($refund->order->items as $item)
                    <div class="flex items-start gap-4 py-3 border-b border-gray-800 last:border-0">
                        <div class="w-16 h-16 rounded-lg bg-gray-800 overflow-hidden flex-shrink-0 border border-gray-700">
                            @if($item->variant && $item->variant->image)
                                <img src="{{ asset('storage/' . $item->variant->image) }}" class="w-full h-full object-cover">
                            @elseif($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-bold">{{ $item->product_name }}</p>
                            @if($item->variant)
                            <div class="flex flex-wrap gap-2 mt-1">
                                @if($item->variant->color)
                                <span class="bg-gray-800 text-gray-400 text-[10px] px-2 py-0.5 rounded border border-gray-700">Màu: {{ $item->variant->color }}</span>
                                @endif
                                @if($item->variant->storage)
                                <span class="bg-gray-800 text-gray-400 text-[10px] px-2 py-0.5 rounded border border-gray-700">Dung lượng: {{ $item->variant->storage }}</span>
                                @endif
                                @if($item->variant->ram)
                                <span class="bg-gray-800 text-gray-400 text-[10px] px-2 py-0.5 rounded border border-gray-700">RAM: {{ $item->variant->ram }}</span>
                                @endif
                            </div>
                            @endif
                            <p class="text-gray-500 text-xs mt-1">Số lượng: <span class="text-white font-medium">x{{ $item->quantity }}</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-orange-400 font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}đ</p>
                            <p class="text-gray-500 text-[10px]">{{ number_format($item->price, 0, ',', '.') }}đ/cái</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Customer & Order Info --}}
            <div class="bg-gray-900 rounded-xl p-5">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Thông tin khách hàng</p>
                            <p class="text-white font-bold text-base">{{ $refund->order->shipping_name ?? $refund->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Số điện thoại</p>
                            <p class="text-cyan-400 font-medium">{{ $refund->order->shipping_phone ?? 'Chưa cung cấp' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Email</p>
                            <p class="text-gray-300 text-sm">{{ $refund->order->email ?? $refund->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Địa chỉ nhận hàng</p>
                            <p class="text-gray-300 text-sm leading-snug">
                                {{ $refund->order->shipping_address }}<br>
                                {{ $refund->order->shipping_district ? $refund->order->shipping_district . ', ' : '' }}{{ $refund->order->shipping_city }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Mã đơn hàng</p>
                            <a href="{{ route('admin.orders.show', $refund->order_id) }}"
                               class="text-cyan-400 hover:text-cyan-300 font-bold font-mono hover:underline text-lg">
                                #{{ $refund->order->order_number ?? $refund->order_id }}
                            </a>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Tổng tiền đơn hàng</p>
                            <p class="text-white font-bold">{{ number_format($refund->order->grand_total ?? 0, 0, ',', '.') }}đ</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Hình thức yêu cầu</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $refund->type === 'return' ? 'bg-orange-500/20 text-orange-400' : 'bg-green-500/20 text-green-400' }}">
                                {{ $refund->type_label }}
                            </span>
                </div>
            </div>

            {{-- Reason & Video --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Reason --}}
                <div class="bg-gray-900 rounded-xl p-5 border border-gray-800">
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                        Lý do hoàn hàng từ khách
                    </p>
                    <p class="text-gray-200 leading-relaxed text-sm italic">"{{ $refund->reason }}"</p>
                </div>

                {{-- Video --}}
                @if($refund->video_path)
                <div class="bg-gray-900 rounded-xl p-5 border border-gray-800">
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Video minh chứng
                    </p>
                    <div class="rounded-lg overflow-hidden border border-gray-700 aspect-video bg-black">
                        <video controls class="w-full h-full" preload="metadata">
                            <source src="{{ asset('storage/' . $refund->video_path) }}" type="video/mp4">
                            Trình duyệt không hỗ trợ video.
                        </video>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-gray-500 text-[10px] truncate max-w-[150px]">{{ $refund->video_original_name }}</span>
                        <a href="{{ asset('storage/' . $refund->video_path) }}" download
                           class="text-cyan-400 hover:text-cyan-300 text-[10px] font-bold uppercase tracking-tighter">
                            Tải xuống ↓
                        </a>
                    </div>
                </div>
                @else
                <div class="bg-gray-900/50 rounded-xl p-5 border border-dashed border-gray-800 flex flex-col items-center justify-center text-center">
                    <svg class="w-8 h-8 text-gray-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <p class="text-gray-600 text-xs">Không có video đính kèm</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Right: Admin Actions --}}
        <div class="space-y-5">

            @if($refund->status === 'pending')

            {{-- Duyệt --}}
            <div class="bg-gray-900 rounded-xl p-5 border border-green-500/20 relative z-50">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <span class="text-green-400">✅</span> Duyệt yêu cầu
                </h3>
                <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST" class="relative z-50">
                    @csrf
                    <textarea name="admin_note" rows="3" placeholder="Ghi chú thành công (tùy chọn)..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:ring-2 focus:ring-green-500 outline-none mb-3 resize-none"></textarea>
                    <input type="submit" value="XÁC NHẬN PHÊ DUYỆT"
                          class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg cursor-pointer">
                </form>
            </div>

            {{-- Từ chối --}}
            <div class="bg-gray-900 rounded-xl p-5 border border-red-500/20 relative z-50">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <span class="text-red-400">❌</span> Từ chối yêu cầu
                </h3>
                <form action="{{ route('admin.refunds.reject', $refund) }}" method="POST" class="relative z-50">
                    @csrf
                    <textarea name="admin_note" rows="3" required placeholder="Lý do từ chối (bắt buộc)..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500 outline-none mb-3 resize-none"></textarea>
                    <input type="submit" value="XÁC NHẬN TỪ CHỐI"
                          class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg cursor-pointer">
                </form>
            </div>

            @elseif($refund->status === 'approved_return')
            
            {{-- Confirm Received Return Items --}}
            <div class="bg-gray-900 rounded-xl p-5 border border-blue-500/30 relative z-50">
                <h3 class="text-blue-400 font-bold mb-3 flex items-center gap-2">
                    📦 Khách đang gửi hàng
                </h3>
                <p class="text-white text-sm mb-4">Mã gửi hàng: <span class="bg-blue-900 text-blue-200 px-2 py-1 rounded font-mono font-bold">{{ $refund->return_code }}</span></p>
                <form action="{{ route('admin.refunds.confirm', $refund) }}" method="POST" class="relative z-50">
                    @csrf
                    <input type="submit" value="XÁC NHẬN ĐÃ NHẬN HÀNG -> HOÀN HÀNG"
                          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg cursor-pointer">
                </form>
            </div>

            @else
            {{-- Already processed --}}
            <div class="bg-gray-900 rounded-xl p-5">
                <h3 class="text-white font-bold mb-3">Kết quả xét duyệt</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide">Quyết định</p>
                        @php
                        $stLbl = [
                            'refunded' => '✅ Đã hoàn hàng',
                            'rejected' => '❌ Đã từ chối'
                        ];
                        @endphp
                        <span class="{{ $refund->status === 'refunded' ? 'text-green-400' : 'text-red-400' }} font-bold">
                            {{ $stLbl[$refund->status] ?? $refund->status }}
                        </span>
                    </div>
                    @if($refund->return_code)
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide mt-2">Mã gửi hàng</p>
                        <p class="text-white font-mono font-bold text-sm bg-gray-800 inline-block px-2 py-1 rounded">{{ $refund->return_code }}</p>
                    </div>
                    @endif
                    @if($refund->admin_note)
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide mt-2">Ghi chú của admin</p>
                        <p class="text-gray-200 text-sm bg-gray-800 p-2 rounded">{{ $refund->admin_note }}</p>
                    </div>
                    @endif
                    @if($refund->reviewed_at)
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide mt-2">Thời gian xử lý</p>
                        <p class="text-gray-300 text-sm">{{ $refund->reviewed_at->format('H:i d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
