@extends('frontend.layouts.app')

@section('content')
<div class="bg-[#f4f6f8] min-h-screen pt-24 pb-20">
    <div class="max-w-6xl mx-auto px-4">
        
        <div class="flex items-center gap-2 mb-6">
            <a href="/" class="text-gray-500 hover:text-red-600 font-medium transition-colors">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-red-600 font-medium transition-colors">Giỏ hàng</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-800 font-bold">Thanh toán</span>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            Thông tin nhận hàng
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Họ và tên <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ auth('web')->check() ? auth('web')->user()->name : '' }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone_input" 
                                       value="{{ auth('web')->check() ? auth('web')->user()->phone ?? '' : '' }}" 
                                       placeholder="Ví dụ: 0987 654 321"
                                       required 
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all text-sm">
                                <p id="phone_error" class="text-[11px] text-red-500 mt-1 hidden font-medium">Số điện thoại không hợp lệ (phải có 10 chữ số và bắt đầu bằng số 0).</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Địa chỉ Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ auth('web')->check() ? auth('web')->user()->email : '' }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Địa chỉ nhận hàng chi tiết <span class="text-red-500">*</span></label>
                            
                            <!-- Khu vực chọn Tỉnh/Thành -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="relative">
                                    <select id="province" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none text-sm bg-white cursor-pointer appearance-none transition-all" required>
                                        <option value="">Tỉnh / Thành phố</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <div class="relative">
                                    <select id="district" disabled class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none text-sm bg-white cursor-pointer appearance-none transition-all disabled:opacity-50 disabled:bg-gray-50" required>
                                        <option value="">Quận / Huyện</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <div class="relative">
                                    <select id="ward" disabled class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none text-sm bg-white cursor-pointer appearance-none transition-all disabled:opacity-50 disabled:bg-gray-50" required>
                                        <option value="">Phường / Xã</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nhập số nhà chi tiết -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </div>
                                <input type="text" id="street_address" placeholder="Tên đường, Tòa nhà, Số nhà chi tiết..." required class="w-full pl-10 px-4 py-2.5 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all text-sm">
                            </div>

                            <!-- Input ẩn để gộp dữ liệu submit về Laravel Backend -->
                            <input type="hidden" name="address" id="final_address" value="{{ auth('web')->check() ? auth('web')->user()->address ?? '' : '' }}">
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            Phương thức thanh toán
                        </h2>
                        <div class="space-y-3">
                            <label class="group border border-gray-200 hover:border-red-500 bg-white has-[:checked]:bg-red-50 has-[:checked]:border-red-500 p-4 rounded-xl flex items-center justify-between cursor-pointer transition-all">
                                <div class="flex items-center gap-4">
                                    <input type="radio" name="payment_method" value="cod" checked class="w-5 h-5 text-red-600">
                                    <div>
                                        <span class="font-bold text-gray-800 block text-sm">Thanh toán khi nhận hàng (COD)</span>
                                        <span class="text-xs text-gray-500 mt-0.5">Tiền mặt khi giao hàng</span>
                                    </div>
                                </div>
                            </label>
                            <label class="group border border-gray-200 hover:border-red-500 bg-white has-[:checked]:bg-red-50 has-[:checked]:border-red-500 p-4 rounded-xl flex items-center justify-between cursor-pointer transition-all">
                                <div class="flex items-center gap-4">
                                    <input type="radio" name="payment_method" value="vnpay" class="w-5 h-5 text-red-600">
                                    <div>
                                        <span class="font-bold text-gray-800 block text-sm">Thanh toán qua VNPAY</span>
                                        <span class="text-xs text-gray-500 mt-0.5">Thẻ ATM, Visa, Mastercard, QR Code</span>
                                    </div>
                                </div>
                                <img src="https://sandbox.vnpayment.vn/paymentv2/Images/brands/logo-vnpay.png" alt="VNPAY" class="h-6 object-contain">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                        <h2 class="font-bold text-lg text-gray-800 mb-4 pb-4 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Đơn hàng của bạn
                        </h2>
                        
                        <div class="max-h-[300px] overflow-y-auto space-y-4 mb-5 pr-2 scrollbar-thin">
                            @php $total = 0; @endphp
                            @foreach(session('checkout_items', []) as $item)
                                @php 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 rounded-lg border border-gray-100 p-1 bg-white relative">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain">
                                        <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">{{ $item['quantity'] }}</span>
                                    </div>
                                    <div class="flex-1 text-sm flex flex-col justify-center">
                                        <p class="font-medium text-gray-800 line-clamp-2 leading-tight">{{ $item['name'] }}</p>
                                        <p class="font-bold text-gray-800 mt-1">{{ number_format($item['price'], 0, ',', '.') }}đ</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-5 bg-red-50 p-3 rounded-xl border border-dashed border-red-200">
                            <label class="block text-xs font-bold text-red-700 mb-2 uppercase">Mã giảm giá</label>
                            <div class="flex gap-2">
                                <input type="text" id="coupon_code_input" readonly placeholder="Chọn voucher..." class="flex-1 px-3 py-2 border border-white rounded-lg bg-white text-xs outline-none cursor-pointer" onclick="openCouponModal()">
                                <button type="button" onclick="openCouponModal()" class="bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-red-700 transition-all">Chọn</button>
                            </div>
                            <div id="coupon-message" class="text-[10px] mt-1.5 font-medium min-h-[15px]"></div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 space-y-3 mb-6 text-sm border border-gray-100 shadow-inner">
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính</span>
                                <span class="font-semibold text-gray-800">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                            <div id="discount-row" class="flex justify-between text-green-600 hidden">
                                <span>Giảm giá</span>
                                <span id="discount-amount" class="font-bold">-0đ</span>
                            </div>
                            <div class="flex justify-between items-center bg-green-50/50 p-3 rounded-xl border border-green-100/50">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-sm font-medium">Vận chuyển</span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-sm font-bold text-green-600 uppercase tracking-wider">Miễn phí</span>
                                    <span class="text-[10px] text-green-500 font-medium">Đặc quyền Smart Store</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-end pt-3 border-t border-gray-200 mt-2">
                                <span class="font-bold text-gray-800">Tổng cộng</span>
                                <div class="text-right">
                                    <span class="block text-xl md:text-2xl font-black text-red-600 leading-none" id="grand-total-display">{{ number_format($total, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl uppercase transition-all shadow-[0_8px_20px_-6px_rgba(220,38,38,0.5)] flex justify-center items-center gap-2">
                            <span>Đặt hàng ngay</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="couponModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl animate-modal-in">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-black text-gray-800 text-lg">Smart Store Voucher</h3>
                <p class="text-xs text-gray-500">Tiết kiệm hơn cho đơn hàng của bạn</p>
            </div>
            <button type="button" onclick="closeCouponModal()" class="text-gray-400 hover:text-red-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 max-h-[450px] overflow-y-auto space-y-4" id="coupon-list-content">
            <div class="flex flex-col items-center justify-center py-10 space-y-3">
                <div class="w-12 h-12 border-4 border-red-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-sm text-gray-500 font-medium">Đang tìm voucher tốt nhất...</p>
            </div>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100">
            <button type="button" onclick="closeCouponModal()" class="w-full py-3.5 bg-gray-900 text-white rounded-xl font-bold uppercase text-xs tracking-widest">Đóng</button>
        </div>
    </div>
</div>

<!-- Modal Thông báo giới hạn COD -->
<div id="codLimitModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl animate-modal-in text-center p-8">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center text-red-600 mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-4 uppercase">Thông báo thanh toán</h3>
        <p class="text-gray-600 mb-8 leading-relaxed">
            Đơn hàng của bạn đã vượt mức <span class="font-bold text-red-600">10 triệu</span> tiền mặt. Vui lòng thanh toán bằng <span class="font-bold text-blue-600">VNPAY</span> để tiếp tục.
        </p>
        <button type="button" onclick="closeCodModal()" class="w-full py-4 bg-gray-900 text-white rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-black transition-all">Tôi đã hiểu</button>
    </div>
</div>

<style>
    .scrollbar-thin::-webkit-scrollbar { width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }
    @keyframes modal-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-modal-in { animation: modal-in 0.2s ease-out; }
</style>

<script>
    const modal = document.getElementById('couponModal');
    const couponInput = document.getElementById('coupon_code_input');

    function openCouponModal() {
        modal.classList.remove('hidden');
        // Load danh sách voucher mỗi khi mở modal
        fetch("{{ route('coupon.list') }}")
        .then(res => res.json())
        .then(data => {
            let html = '';
            if(!data || data.length === 0) {
                html = `<div class="text-center py-10"><p class="text-gray-500 text-sm">Hiện tại không có mã nào phù hợp.</p></div>`;
            } else {
                data.forEach(cp => {
                    const valueFormatted = cp.type === 'percent' ? cp.value + '%' : new Intl.NumberFormat('vi-VN').format(cp.value) + 'đ';
                    html += `
                        <div class="relative border-2 border-gray-100 p-4 rounded-2xl flex items-center gap-4 hover:border-red-500 hover:bg-red-50/30 cursor-pointer transition-all" onclick="selectCoupon('${cp.code}')">
                            <div class="w-16 h-16 bg-red-600 rounded-xl flex flex-col items-center justify-center text-white shrink-0 shadow-lg">
                                <span class="text-sm font-black">${cp.code}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-gray-800 text-sm">Giảm ngay ${valueFormatted}</h4>
                                <p class="text-[10px] text-gray-500">Mã ưu đãi đặc biệt</p>
                                <p class="text-[10px] text-red-600 font-bold mt-1">HSD: ${new Date(cp.end_date ?? cp.expires_at).toLocaleDateString('vi-VN')}</p>
                            </div>
                            <div class="text-red-600 font-black text-xs uppercase">Dùng</div>
                        </div>`;
                });
            }
            document.getElementById('coupon-list-content').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('coupon-list-content').innerHTML = `<div class="text-center py-10 text-red-500 text-sm">Lỗi tải dữ liệu. Vui lòng thử lại.</div>`;
        });
    }

    function closeCouponModal() {
        modal.classList.add('hidden');
    }

    function selectCoupon(code) {
        couponInput.value = code;
        closeCouponModal();
        applyCoupon(code);
    }

    function applyCoupon(code) {
        fetch("{{ route('coupon.apply') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            const msg = document.getElementById('coupon-message');
            if(data.success) {
                msg.className = "text-[11px] mt-1.5 text-green-600 font-bold flex items-center gap-1";
                msg.innerHTML = `✓ Áp dụng thành công: -${new Intl.NumberFormat('vi-VN').format(data.discount)}đ`;
                document.getElementById('discount-row').classList.remove('hidden');
                document.getElementById('discount-amount').innerText = '-' + new Intl.NumberFormat('vi-VN').format(data.discount) + 'đ';
                document.getElementById('grand-total-display').innerText = new Intl.NumberFormat('vi-VN').format(data.new_total) + 'đ';
            } else {
                msg.className = "text-[11px] mt-1.5 text-red-600 font-medium";
                msg.innerText = "× " + data.message;
                couponInput.value = '';
            }
        });
    }

    // Tích hợp API Tỉnh/Thành phố Việt Nam
    document.addEventListener("DOMContentLoaded", function() {
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        const streetInput = document.getElementById('street_address');
        const phoneInput = document.getElementById('phone_input');
        const phoneError = document.getElementById('phone_error');
        const finalAddress = document.getElementById('final_address');
        const form = document.getElementById('checkout-form');

        // Logic Validate và Định dạng Số điện thoại
        phoneInput.addEventListener('input', function(e) {
            // Chỉ giữ lại số
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,3})(\d{0,3})/);
            
            // Tự động thêm dấu cách: 0987 654 321
            if (x[1]) {
                e.target.value = x[1] + (x[2] ? ' ' + x[2] : '') + (x[3] ? ' ' + x[3] : '');
            }

            // Kiểm tra tính hợp lệ (Regex VN: bắt đầu bằng 0, có 10 số)
            const rawValue = e.target.value.replace(/\s/g, '');
            const vnPhoneRegex = /^(0[3|5|7|8|9])[0-9]{8}$/;
            
            if (rawValue.length > 0 && !vnPhoneRegex.test(rawValue)) {
                phoneInput.classList.add('border-red-500', 'bg-red-50');
                phoneError.classList.remove('hidden');
            } else {
                phoneInput.classList.remove('border-red-500', 'bg-red-50');
                phoneError.classList.add('hidden');
            }
        });

        const fetchAPI = async (url) => {
            try {
                const res = await fetch(url);
                return await res.json();
            } catch (err) {
                console.error("Lỗi khi tải dữ liệu địa giới hành chính:", err);
                return null;
            }
        };

        // 1. Tải danh sách Tỉnh/Thành
        fetchAPI('https://provinces.open-api.vn/api/?depth=1').then(data => {
            if(!data) return;
            let html = '<option value="">Tỉnh / Thành phố</option>';
            data.forEach(item => {
                html += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`;
            });
            provinceSelect.innerHTML = html;
        });

        // 2. Tải danh sách Quận/Huyện khi chọn Tỉnh
        provinceSelect.addEventListener('change', function() {
            districtSelect.disabled = true;
            wardSelect.disabled = true;
            districtSelect.innerHTML = '<option value="">Đang tải Quận / Huyện...</option>';
            wardSelect.innerHTML = '<option value="">Phường / Xã</option>';
            
            if(this.value) {
                fetchAPI(`https://provinces.open-api.vn/api/p/${this.value}?depth=2`).then(data => {
                    if(!data) return;
                    let html = '<option value="">Chọn Quận / Huyện</option>';
                    data.districts.forEach(item => {
                        html += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`;
                    });
                    districtSelect.innerHTML = html;
                    districtSelect.disabled = false;
                });
            } else {
                districtSelect.innerHTML = '<option value="">Quận / Huyện</option>';
            }
        });

        // 3. Tải danh sách Phường/Xã khi chọn Quận
        districtSelect.addEventListener('change', function() {
            wardSelect.disabled = true;
            wardSelect.innerHTML = '<option value="">Đang tải Phường / Xã...</option>';
            
            if(this.value) {
                fetchAPI(`https://provinces.open-api.vn/api/d/${this.value}?depth=2`).then(data => {
                    if(!data) return;
                    let html = '<option value="">Chọn Phường / Xã</option>';
                    data.wards.forEach(item => {
                        html += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`;
                    });
                    wardSelect.innerHTML = html;
                    wardSelect.disabled = false;
                });
            } else {
                wardSelect.innerHTML = '<option value="">Phường / Xã</option>';
            }
        });

        // 4. Kiểm tra giới hạn COD (> 10 triệu) và gộp dữ liệu vào thẻ hidden trước khi Submit form
        form.addEventListener('submit', function(e) {
            // Lấy tổng tiền hiện tại từ text (xóa 'đ' và '.')
            const totalText = document.getElementById('grand-total-display').innerText;
            const totalValue = parseInt(totalText.replace(/[^\d]/g, ''));
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            if (paymentMethod === 'cod' && totalValue > 10000000) {
                e.preventDefault();
                document.getElementById('codLimitModal').classList.remove('hidden');
                return;
            }

            const pName = provinceSelect.options[provinceSelect.selectedIndex]?.getAttribute('data-name') || '';
            const dName = districtSelect.options[districtSelect.selectedIndex]?.getAttribute('data-name') || '';
            const wName = wardSelect.options[wardSelect.selectedIndex]?.getAttribute('data-name') || '';
            const street = streetInput.value.trim();

            if (pName && dName && wName && street) {
                // Sẽ lưu String chuẩn xác ví dụ: "Số 123 Đường A, Phường B, Quận C, Tỉnh D"
                finalAddress.value = `${street}, ${wName}, ${dName}, ${pName}`;
            } else {
                // Phòng trường hợp lỗi select
                e.preventDefault();
                alert("Vui lòng chọn đầy đủ thông tin Tỉnh/Thành phố, Quận/Huyện, Phường/Xã!");
            }
        });
    });

    function closeCodModal() {
        document.getElementById('codLimitModal').classList.add('hidden');
        // Tự động chọn VNPAY cho khách
        const vnpayRadio = document.querySelector('input[name="payment_method"][value="vnpay"]');
        if(vnpayRadio) vnpayRadio.checked = true;
    }
</script>
@endsection