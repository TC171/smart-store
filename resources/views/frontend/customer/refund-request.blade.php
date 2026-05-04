@extends('frontend.layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="max-w-3xl mx-auto px-4">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('customer.orders') }}" class="text-gray-400 hover:text-orange-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800">Yêu cầu <span class="text-orange-500">Hoàn hàng</span></h1>
                <p class="text-gray-500 text-sm">Đơn hàng #{{ $order->order_number }}</p>
            </div>
        </div>

        @if($existingRequest)
        {{-- Already have a pending or approved request --}}
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-orange-100 mb-6">
            <div class="flex items-center gap-4 mb-6">
                @if($existingRequest->status === 'pending')
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Yêu cầu đang chờ xét duyệt</h2>
                        <p class="text-sm text-gray-500">Gửi lúc {{ $existingRequest->created_at->format('H:i d/m/Y') }}</p>
                    </div>
                    <span class="ml-auto px-4 py-1.5 text-xs font-black uppercase rounded-full bg-yellow-100 text-yellow-600">Đang chờ</span>
                @else
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Yêu cầu đã được duyệt!</h2>
                        <p class="text-sm text-gray-500">Mã trả hàng: <strong class="text-blue-600">{{ $existingRequest->return_code }}</strong></p>
                    </div>
                    <span class="ml-auto px-4 py-1.5 text-xs font-black uppercase rounded-full bg-blue-100 text-blue-600">Đã duyệt</span>
                @endif
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Loại yêu cầu</p>
                <p class="font-semibold text-gray-700">Hoàn hàng</p>
                <p class="text-xs text-gray-400 uppercase font-bold mt-3 mb-1">Lý do</p>
                <p class="text-gray-600">{{ $existingRequest->reason }}</p>
                @if($existingRequest->status === 'rejected' && $existingRequest->admin_note)
                <p class="text-xs text-red-400 uppercase font-bold mt-3 mb-1">Lý do từ chối (từ Admin)</p>
                <p class="text-red-600 font-medium">{{ $existingRequest->admin_note }}</p>
                @endif
                @if($existingRequest->video_path)
                <p class="text-xs text-gray-400 uppercase font-bold mt-3 mb-1">Video đính kèm</p>
                <div class="flex items-center gap-2 text-green-600 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.876V15.124a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ $existingRequest->video_original_name ?? 'Video đã tải lên' }}
                </div>
                @endif
            </div>
        </div>
        @else

        {{-- Flash messages --}}
        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-5 py-4 rounded-xl mb-6">
            <ul class="space-y-1 text-sm">
                @foreach($errors->all() as $error)
                <li class="flex items-center gap-2"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Order Summary --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="font-black text-gray-700 text-sm uppercase tracking-widest">Thông tin đơn hàng</h2>
            </div>
            <div class="px-6 py-4 space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4">
                    @if($item->variant && $item->variant->product && $item->variant->product->thumbnail)
                    <img src="{{ asset('storage/' . $item->variant->product->thumbnail) }}" alt="{{ $item->product_name }}" class="w-14 h-14 object-cover rounded-xl border border-gray-100">
                    @else
                    <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $item->product_name }}</p>
                        @if($item->variant)
                        <p class="text-xs text-gray-500 mt-1">
                            @if($item->variant->color)Màu sắc: {{ $item->variant->color }}@endif
                            @if($item->variant->storage) | Dung lượng: {{ $item->variant->storage }}@endif
                            @if($item->variant->ram) | RAM: {{ $item->variant->ram }}@endif
                        </p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">x{{ $item->quantity }}</p>
                    </div>
                    <span class="font-black text-orange-500">{{ number_format($item->subtotal, 0, ',', '.') }}đ</span>
                </div>
                @endforeach
                <div class="border-t border-gray-100 pt-3 flex justify-between font-black text-gray-800">
                    <span>Tổng đơn hàng</span>
                    <span class="text-orange-500">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>

        {{-- Important Note --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div class="text-sm">
                <p class="font-bold text-amber-700 mb-1">⚠️ Yêu cầu bắt buộc</p>
                <ul class="text-amber-600 space-y-1 text-xs">
                    <li>• Video phải quay từ lúc <strong>chưa bóc kiện hàng</strong> cho đến khi phát hiện vấn đề</li>
                    <li>• Video phải rõ nét, thấy được mã vận đơn trên kiện hàng</li>
                    <li>• Định dạng: MP4, AVI, MOV, WebM — Tối đa 20MB</li>
                    <li>• Yêu cầu sẽ được xem xét trong 1–3 ngày làm việc</li>
                </ul>
            </div>
        </div>

        {{-- Refund Form --}}
        <form action="{{ route('customer.orders.refund.store', $order) }}"
              method="POST"
              enctype="multipart/form-data"
              x-data="refundForm()"
              class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

            @csrf
            
            {{-- Hidden requested 'type' since we removed the type selection UI --}}
            <input type="hidden" name="type" value="return">

            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-white">
                <h2 class="font-black text-gray-700 text-sm uppercase tracking-widest">Chi tiết yêu cầu</h2>
            </div>

            <div class="px-6 py-6 space-y-6">

                {{-- Reason --}}
                <div>
                    <label for="reason" class="block text-sm font-bold text-gray-700 mb-2">Lý do hoàn hàng <span class="text-red-500">*</span></label>
                    <textarea name="reason" id="reason" rows="4" required minlength="10" maxlength="1000"
                              placeholder="Mô tả chi tiết vấn đề với sản phẩm (ví dụ: hàng bị vỡ, sai màu, thiếu phụ kiện...)"
                              class="w-full border-2 border-gray-200 rounded-2xl px-4 py-3 text-sm focus:border-orange-400 focus:ring-4 focus:ring-orange-100 outline-none transition-all resize-none">{{ old('reason') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Tối thiểu 10 ký tự</p>
                </div>

                {{-- Video Upload --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Video bóc hàng <span class="text-red-500">*</span></label>

                    <div @click="$refs.videoInput.click()"
                         :class="videoFile ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-orange-300 bg-white'"
                         class="border-2 border-dashed rounded-2xl p-6 text-center cursor-pointer transition-all group">

                        <input type="file" name="video" x-ref="videoInput" accept="video/*"
                               @change="handleVideoChange($event)" class="hidden" required>

                        <template x-if="!videoFile">
                            <div>
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-200 transition-colors">
                                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.876V15.124a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-700 mb-1">Chọn video bóc hàng</p>
                                <p class="text-xs text-gray-400">MP4, AVI, MOV, WebM — Tối đa 20MB</p>
                            </div>
                        </template>

                        <template x-if="videoFile">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.876V15.124a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex-1 text-left min-w-0">
                                    <p class="font-semibold text-green-700 text-sm truncate" x-text="videoFile.name"></p>
                                    <p class="text-xs text-gray-400" x-text="formatSize(videoFile.size)"></p>
                                </div>
                                <button type="button" @click.stop="removeVideo()" class="text-red-400 hover:text-red-600 transition-colors flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Size error --}}
                    <p x-show="sizeError" x-text="sizeError" class="text-red-500 text-xs mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </p>
                </div>

            </div>

            {{-- Submit --}}
            <div class="px-6 pb-6">
                <button type="submit"
                        :disabled="submitting"
                        class="w-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-orange-200 text-sm uppercase tracking-widest flex items-center justify-center gap-2">
                    <template x-if="!submitting">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            Gửi yêu cầu hoàn hàng
                        </span>
                    </template>
                    <template x-if="submitting">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Đang gửi...
                        </span>
                    </template>
                </button>
            </div>
        </form>
        @endif

    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
function refundForm() {
    return {
        videoFile: null,
        sizeError: '',
        submitting: false,

        handleVideoChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            const maxSize = 20 * 1024 * 1024; // 20MB
            if (file.size > maxSize) {
                this.sizeError = 'Video vượt quá 20MB. Vui lòng chọn file nhỏ hơn.';
                this.videoFile = null;
                event.target.value = '';
                return;
            }

            this.sizeError = '';
            this.videoFile = file;
        },

        removeVideo() {
            this.videoFile = null;
            this.sizeError = '';
            this.$refs.videoInput.value = '';
        },

        formatSize(bytes) {
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }
    }
}
</script>
@endsection
