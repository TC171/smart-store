@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('shipper.deliveries.index') }}" class="text-gray-400 hover:text-white transition">← Đơn hàng</a>
    <span class="text-gray-600">/</span>
    <h1 class="text-xl font-bold text-white">{{ $order->order_number ? $order->order_number : '#' . $order->id }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Thông tin đơn --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Trạng thái và Tiến trình --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-white">Trạng thái đơn hàng</h2>
                @php
                    $statusColors = [
                        'confirmed'       => 'bg-yellow-500/20 text-yellow-400',
                        'shipping'        => 'bg-cyan-500/20 text-cyan-400',
                        'completed'       => 'bg-green-500/20 text-green-400',
                        'failed_delivery' => 'bg-red-500/20 text-red-400',
                    ];
                    $statusLabels = [
                        'confirmed'       => '📋 Chờ đi giao',
                        'shipping'        => '🚴 Đang đi giao',
                        'completed'       => '✅ Giao thành công',
                        'failed_delivery' => '❌ Giao thất bại',
                    ];
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>

            {{-- Timeline 3 bước rút gọn (Bỏ bước nhận kho) --}}
            <div class="flex items-center gap-2 mt-4 px-4">
                {{-- Bước 1: Chờ giao --}}
                <div class="flex-1 text-center">
                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm {{ in_array($order->status, ['confirmed','shipping','completed','failed_delivery']) ? 'bg-indigo-500 text-white' : 'bg-gray-700 text-gray-400' }}">📋</div>
                    <div class="text-xs text-gray-400 mt-1">Chờ giao</div>
                </div>
                <div class="flex-1 h-0.5 {{ in_array($order->status, ['shipping','completed','failed_delivery']) ? 'bg-cyan-500' : 'bg-gray-700' }}"></div>
                
                {{-- Bước 2: Đang giao --}}
                <div class="flex-1 text-center">
                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm {{ in_array($order->status, ['shipping','completed','failed_delivery']) ? 'bg-cyan-500 text-white' : 'bg-gray-700 text-gray-400' }}">🚴</div>
                    <div class="text-xs text-gray-400 mt-1">Đang giao</div>
                </div>
                <div class="flex-1 h-0.5 {{ $order->status === 'completed' ? 'bg-green-500' : ($order->status === 'failed_delivery' ? 'bg-red-500' : 'bg-gray-700') }}"></div>
                
                {{-- Bước 3: Kết quả --}}
                <div class="flex-1 text-center">
                    @if($order->status === 'failed_delivery')
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm bg-red-500 text-white">✗</div>
                        <div class="text-xs text-red-400 mt-1">Thất bại</div>
                    @else
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-700 text-gray-400' }}">✓</div>
                        <div class="text-xs text-gray-400 mt-1">Kết quả</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Thông tin khách hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4 flex items-center gap-2">
                <span class="p-1.5 bg-gray-800 rounded-lg">📍</span> Thông tin giao hàng
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-400 mb-0.5">Người nhận</div>
                    <div class="text-white font-bold text-base">{{ $order->shipping_name }}</div>
                </div>
                <div>
                    <div class="text-gray-400 mb-0.5">Số điện thoại</div>
                    <a href="tel:{{ $order->shipping_phone }}" class="text-cyan-400 font-bold text-base hover:underline flex items-center gap-1">
                        📞 {{ $order->shipping_phone }}
                    </a>
                </div>
                <div class="col-span-2">
                    <div class="text-gray-400 mb-0.5">Địa chỉ nhận hàng</div>
                    <div class="text-white leading-relaxed">{{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}</div>
                </div>
                @if($order->note)
                <div class="col-span-2">
                    <div class="text-gray-400 mb-0.5">Ghi chú từ khách hàng</div>
                    <div class="text-yellow-300 bg-yellow-500/10 border border-yellow-500/30 rounded-lg px-3 py-2 whitespace-pre-line italic">
                        "{{ $order->note }}"
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">🛍️ Chi tiết đơn hàng</h2>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center gap-3 p-3 bg-gray-800/50 rounded-lg">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium">{{ $item->product_name }}</div>
                        @if($item->sku || $item->variant?->sku)
                            <div class="text-gray-500 text-xs">Mã: {{ $item->sku ?: $item->variant?->sku }}</div>
                        @endif
                    </div>
                    <div class="text-right text-sm shrink-0">
                        <div class="text-gray-300">x{{ $item->quantity }}</div>
                        <div class="text-cyan-400 font-medium">{{ number_format($item->price * $item->quantity) }}₫</div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-5 pt-4 border-t border-gray-800 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Tổng tiền đơn hàng</span>
                    <span class="text-white font-bold">{{ number_format($order->total_amount) }}₫</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-gray-800 rounded-lg">
                    <span class="text-gray-300 font-medium">Số tiền cần thu (COD)</span>
                    @if($order->payment_status === 'paid')
                        <span class="text-green-400 font-bold">0₫ (Đã thanh toán)</span>
                    @else
                        <span class="text-orange-400 font-extrabold text-lg">{{ number_format($order->total_amount) }}₫</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Khu vực thao tác Shipper --}}
    <div class="space-y-5">
        
        {{-- Nút thao tác tùy theo trạng thái --}}
        @if($order->status === 'confirmed')
            {{-- Bước: Chấp nhận đi giao --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 sticky top-20">
                <h3 class="text-white font-bold mb-2">🚀 Sẵn sàng giao hàng?</h3>
                <p class="text-gray-400 text-sm mb-4">Bấm xác nhận để bắt đầu quá trình giao đơn hàng này tới tay khách hàng.</p>
                <form action="{{ route('shipper.deliveries.pickup', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold transition shadow-lg shadow-indigo-500/20 text-base">
                        Bắt đầu giao hàng ngay
                    </button>
                </form>
            </div>

        @elseif($order->status === 'shipping')
            {{-- Nút mở modal --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 sticky top-20">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse"></span>
                    <h3 class="text-white font-bold">Đang trong quá trình giao...</h3>
                </div>
                <button type="button" onclick="openDeliveryModal()"
                    class="w-full bg-cyan-600 hover:bg-cyan-700 text-white py-3 rounded-xl font-bold transition shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2">
                    📋 Cập nhật kết quả giao hàng
                </button>
            </div>

            {{-- ===== MODAL ===== --}}
            <div id="deliveryModal"
                 class="fixed inset-0 z-[9999] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
                 onclick="closeModalOnBackdrop(event)">

                <div id="deliveryModalContent"
                     class="relative w-full max-w-md bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl overflow-hidden scale-95 opacity-0 transition-all duration-300">

                    {{-- Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                        <h3 class="text-white font-bold text-lg">Cập nhật kết quả giao hàng</h3>
                        <button onclick="closeDeliveryModal()" class="text-gray-500 hover:text-white transition p-1 rounded-lg hover:bg-gray-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- Unified Form --}}
                        <form action="{{ route('shipper.deliveries.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            
                            {{-- Select Status --}}
                            <div class="mb-5">
                                <label class="block text-sm font-medium text-gray-400 mb-3">Trạng thái giao hàng</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status" value="completed" class="peer sr-only" checked onchange="toggleReasonForm(this.value)">
                                        <div class="p-3 text-center rounded-xl border border-gray-700 bg-gray-800/50 text-gray-500 peer-checked:bg-green-600/20 peer-checked:border-green-500 peer-checked:text-green-400 transition font-bold text-sm">
                                            ✅ Thành công
                                        </div>
                                    </label>
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status" value="failed_delivery" class="peer sr-only" onchange="toggleReasonForm(this.value)">
                                        <div class="p-3 text-center rounded-xl border border-gray-700 bg-gray-800/50 text-gray-500 peer-checked:bg-red-500/20 peer-checked:border-red-500 peer-checked:text-red-400 transition font-bold text-sm">
                                            ❌ Thất bại
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Dynamic Note / Reason Field --}}
                            <div class="mb-6">
                                <label id="noteLabel" class="block text-sm font-medium text-gray-400 mb-2">Ghi chú (Tùy chọn)</label>
                                
                                {{-- Textarea cho thành công --}}
                                <textarea id="successNote" name="note" rows="2" placeholder="VD: Đã giao cho người thân, gửi bảo vệ..."
                                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none resize-none transition block"></textarea>
                                
                                {{-- Dropdown cho thất bại --}}
                                <select id="failReason" name="note_fail" disabled
                                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none transition hidden">
                                    <option value="">-- Chọn lý do thất bại --</option>
                                    <option value="Khách hàng không nghe máy">Khách hàng không nghe máy</option>
                                    <option value="Khách hàng hẹn ngày khác">Khách hàng hẹn ngày khác</option>
                                    <option value="Sai số điện thoại/Địa chỉ">Sai số điện thoại/Địa chỉ</option>
                                    <option value="Khách từ chối nhận hàng">Khách từ chối nhận hàng</option>
                                </select>
                            </div>

                            <button type="submit" id="submitBtn"
                                class="w-full bg-green-600 hover:bg-green-700 active:scale-[.98] text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-green-900/30 flex items-center justify-center gap-2">
                                Lưu kết quả giao hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function openDeliveryModal() {
                    const m = document.getElementById('deliveryModal');
                    const mc = document.getElementById('deliveryModalContent');
                    m.classList.remove('hidden');
                    m.classList.add('flex');
                    void mc.offsetWidth;
                    mc.classList.remove('scale-95', 'opacity-0');
                    mc.classList.add('scale-100', 'opacity-100');
                }
                function closeDeliveryModal() {
                    const m = document.getElementById('deliveryModal');
                    const mc = document.getElementById('deliveryModalContent');
                    mc.classList.remove('scale-100', 'opacity-100');
                    mc.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        m.classList.add('hidden');
                        m.classList.remove('flex');
                    }, 300);
                }
                function closeModalOnBackdrop(e) {
                    if (e.target.id === 'deliveryModal' || e.target === e.currentTarget) {
                        closeDeliveryModal();
                    }
                }

                function toggleReasonForm(status) {
                    const successNote = document.getElementById('successNote');
                    const failReason = document.getElementById('failReason');
                    const noteLabel = document.getElementById('noteLabel');
                    const submitBtn = document.getElementById('submitBtn');

                    if (status === 'completed') {
                        // Show success note
                        successNote.classList.remove('hidden');
                        successNote.removeAttribute('disabled');
                        
                        // Hide fail dropdown
                        failReason.classList.add('hidden');
                        failReason.setAttribute('disabled', 'disabled');
                        failReason.removeAttribute('required');

                        noteLabel.textContent = 'Ghi chú (Tùy chọn)';
                        
                        // Update button style
                        submitBtn.className = 'w-full bg-green-600 hover:bg-green-700 active:scale-[.98] text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-green-900/30 flex items-center justify-center gap-2';
                        submitBtn.innerHTML = 'Lưu kết quả giao hàng';
                    } else {
                        // Hide success note
                        successNote.classList.add('hidden');
                        successNote.setAttribute('disabled', 'disabled');
                        
                        // Show fail dropdown
                        failReason.classList.remove('hidden');
                        failReason.removeAttribute('disabled');
                        failReason.setAttribute('required', 'required');

                        noteLabel.textContent = 'Lý do thất bại (Bắt buộc)';
                        
                        // Update button style
                        submitBtn.className = 'w-full bg-red-600 hover:bg-red-700 active:scale-[.98] text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-red-900/30 flex items-center justify-center gap-2';
                        submitBtn.innerHTML = 'Báo cáo giao thất bại';
                    }
                }
            </script>

        @elseif($order->status === 'completed')
            <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-6 text-center shadow-inner">
                <div class="text-4xl mb-2 text-green-500">🏆</div>
                <div class="font-bold text-green-400 text-lg">Giao thành công!</div>
                <div class="text-gray-400 text-sm mt-1">Lúc: {{ $order->completed_at?->format('H:i, d/m/Y') }}</div>
            </div>

        @elseif($order->status === 'failed_delivery')
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-center shadow-inner">
                <div class="text-4xl mb-2 text-red-500">🚫</div>
                <div class="font-bold text-red-400 text-lg">Giao hàng thất bại</div>
                <p class="text-gray-400 text-sm mt-2 leading-relaxed">Đơn hàng đã được đánh dấu thất bại và chờ Admin xử lý hoàn hàng/giao lại.</p>
                @if($order->note)
                <div class="mt-4 text-left p-3 bg-red-900/30 border border-red-500/30 rounded-lg">
                    <span class="text-red-300 text-xs font-bold uppercase tracking-wider block mb-1">Lý do thất bại:</span>
                    <span class="text-red-100 text-sm italic whitespace-pre-line">{{ $order->note }}</span>
                </div>
                @endif
            </div>
        @endif

    </div>
</div>

@endsection