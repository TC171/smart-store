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
                        'refunded'        => ['bg-green-500/20  text-green-400  border border-green-500/40',  '✅ Đã hoàn tiền'],
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

            {{-- Customer & Order Info --}}
            <div class="bg-gray-900 rounded-xl p-5 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Khách hàng</p>
                    <p class="text-white font-semibold">{{ $refund->user->name ?? 'N/A' }}</p>
                    <p class="text-gray-400 text-sm">{{ $refund->user->email ?? '' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Đơn hàng</p>
                    <a href="{{ route('admin.orders.show', $refund->order_id) }}"
                       class="text-cyan-400 hover:text-cyan-300 font-semibold font-mono hover:underline">
                        #{{ $refund->order->order_number ?? $refund->order_id }}
                    </a>
                    <p class="text-gray-400 text-sm">{{ number_format($refund->order->grand_total ?? 0, 0, ',', '.') }}đ</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Loại yêu cầu</p>
                    <span class="{{ $refund->type === 'return' ? 'text-orange-400' : 'text-green-400' }} font-bold">
                        {{ $refund->type_label }}
                    </span>
                </div>
                @if($refund->reviewed_at)
                <div>
                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wide">Đã xét duyệt</p>
                    <p class="text-white text-sm">{{ $refund->reviewed_at->format('H:i - d/m/Y') }}</p>
                </div>
                @endif
            </div>

            {{-- Reason --}}
            <div class="bg-gray-900 rounded-xl p-5">
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-3">Lý do hoàn hàng từ khách</p>
                <p class="text-gray-200 leading-relaxed">{{ $refund->reason }}</p>
            </div>

            {{-- Video --}}
            @if($refund->video_path)
            <div class="bg-gray-900 rounded-xl p-5">
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-3">Video bóc hàng</p>
                <div class="rounded-xl overflow-hidden border border-gray-700">
                    <video controls class="w-full max-h-96 bg-black" preload="metadata">
                        <source src="{{ asset('storage/' . $refund->video_path) }}" type="video/mp4">
                        <source src="{{ asset('storage/' . $refund->video_path) }}" type="video/webm">
                        Trình duyệt của bạn không hỗ trợ video.
                    </video>
                </div>
                <p class="text-gray-500 text-xs mt-2">{{ $refund->video_original_name }}</p>
                <a href="{{ asset('storage/' . $refund->video_path) }}" download
                   class="inline-flex items-center gap-2 mt-2 text-cyan-400 hover:text-cyan-300 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Tải xuống video
                </a>
            </div>
            @endif

            {{-- Order Items --}}
            <div class="bg-gray-900 rounded-xl p-5">
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-3">Sản phẩm trong đơn hàng</p>
                <div class="space-y-3">
                    @foreach($refund->order->items as $item)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-800 last:border-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium truncate">{{ $item->product_name }}</p>
                            <p class="text-gray-400 text-xs">x{{ $item->quantity }}</p>
                        </div>
                        <span class="text-orange-400 font-bold text-sm">{{ number_format($item->subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

            {{-- Right: Admin Actions --}}
        <div class="space-y-5">

            @if($refund->status === 'pending')

            {{-- Approve --}}
            <div class="bg-gray-900 rounded-xl p-5">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Duyệt yêu cầu
                </h3>
                <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST">
                    @csrf
                    <textarea name="admin_note" rows="3" placeholder="Ghi chú (tùy chọn)..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:ring-2 focus:ring-green-500 outline-none mb-3 resize-none"></textarea>
                    <button type="submit"
                            onclick="return confirm('Xác nhận duyệt yêu cầu này? Nếu hoàn hàng, sẽ tự động sinh mã gửi hàng.')"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                        ✅ Phê duyệt
                    </button>
                </form>
            </div>

            {{-- Reject --}}
            <div class="bg-gray-900 rounded-xl p-5">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Từ chối yêu cầu
                </h3>
                <form action="{{ route('admin.refunds.reject', $refund) }}" method="POST">
                    @csrf
                    <textarea name="admin_note" rows="3" required placeholder="Lý do từ chối (bắt buộc)..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500 outline-none mb-3 resize-none"></textarea>
                    <button type="submit"
                            onclick="return confirm('Xác nhận từ chối yêu cầu phần nàn này?')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                        ❌ Từ chối
                    </button>
                </form>
            </div>

            @elseif($refund->status === 'approved_return')
            
            {{-- Confirm Received Return Items --}}
            <div class="bg-gray-900 rounded-xl p-5 border border-blue-500/30">
                <h3 class="text-blue-400 font-bold mb-3 flex items-center gap-2">
                    📦 Khách đang gửi hàng
                </h3>
                <p class="text-white text-sm mb-4">Mã gửi hàng: <span class="bg-blue-900 text-blue-200 px-2 py-1 rounded font-mono font-bold">{{ $refund->return_code }}</span></p>
                <form action="{{ route('admin.refunds.confirm', $refund) }}" method="POST">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Chỉ xác nhận khi bạn đã NHẬN ĐƯỢC HÀNG từ khách? Đơn hàng sẽ chuyển sang Đã hoàn tiền!')"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                        Xác nhận Đã nhận hàng -> Hoàn tiền
                    </button>
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
                                'refunded' => '✅ Đã hoàn tiền',
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
