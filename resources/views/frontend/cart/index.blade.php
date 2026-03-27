@extends('frontend.layouts.app')

@section('content')
<div class="bg-[#f4f6f8] min-h-screen pt-24 pb-20">
    <div class="max-w-6xl mx-auto px-4">
        
        <div class="flex items-center gap-2 mb-6">
            <a href="/" class="text-gray-500 hover:text-red-600 font-medium">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-800 font-bold">Giỏ hàng</span>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        @php
            $cart = session('cart', []);
        @endphp

        @if(count($cart) > 0)
            <form action="{{ route('checkout.index') }}" method="GET" id="cart-form">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="lg:col-span-2 space-y-4">
                        
                        <div class="bg-white p-4 rounded-xl shadow-sm flex items-center justify-between">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" id="check-all" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                                <span class="font-medium text-gray-700 group-hover:text-red-600 transition-colors">
                                    Chọn tất cả (<span id="total-count-label">0</span>)
                                </span>
                            </label>

                            <button type="button" id="btn-delete-selected" class="text-sm text-gray-500 hover:text-red-600 font-medium flex items-center gap-1 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Xóa
                            </button>
                        </div>

                        @foreach($cart as $id => $item)
                            <div class="bg-white p-4 rounded-xl shadow-sm flex gap-4 relative border border-transparent hover:border-gray-200 transition-all group">
                                
                                <a href="{{ route('cart.remove', $id) }}" 
                                   class="absolute top-3 right-3 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center shadow-md hover:bg-red-700 hover:scale-110 transition-all z-10" 
                                   title="Xóa sản phẩm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>

                                <div class="pt-8">
                                    <input type="checkbox" name="selected_items[]" value="{{ $id }}" 
                                           class="item-checkbox w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer"
                                           data-id="{{ $id }}" data-price="{{ $item['price'] }}">
                                </div>

                                <div class="w-24 h-24 md:w-32 md:h-32 flex-shrink-0 border border-gray-100 rounded-lg p-1 bg-white">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain">
                                </div>

                                <div class="flex-1 flex flex-col justify-between pr-6 md:pr-8">
                                    <div>
                                        <a href="#" class="font-bold text-gray-800 text-sm md:text-base line-clamp-2 hover:text-red-600 transition-colors leading-snug pr-2">
                                            {{ $item['name'] }}
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">Phân loại: <span class="text-gray-700">{{ $item['variant'] }}</span></p>
                                    </div>
                                    
                                    <div class="mt-3 flex flex-col md:flex-row md:items-end justify-between gap-3">
                                        <div>
                                            <p class="font-bold text-red-600 text-lg">
                                                {{ number_format($item['price'], 0, ',', '.') }}đ
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-4 w-full md:w-auto">
                                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                                                <button type="button" class="btn-qty w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 border-r border-gray-300" data-action="minus" data-id="{{ $id }}">-</button>
                                                <input type="text" class="input-qty w-12 h-8 text-center text-sm font-medium border-none focus:ring-0 p-0 text-gray-800" data-id="{{ $id }}" value="{{ $item['quantity'] }}">
                                                <button type="button" class="btn-qty w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 border-l border-gray-300" data-action="plus" data-id="{{ $id }}">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white p-5 rounded-xl shadow-sm sticky top-24 border border-gray-100">
                            <h3 class="font-bold text-lg text-gray-800 mb-4 pb-3 border-b border-gray-100">Tóm tắt đơn hàng</h3>
                            
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-gray-600 font-medium">Tổng thanh toán:</span>
                                <span class="text-2xl font-black text-red-600" id="total-price">0đ</span>
                            </div>

                            <ul class="text-sm text-gray-500 space-y-2 mb-6 ml-4 list-disc marker:text-gray-300">
                                <li>Phí vận chuyển sẽ được tính ở trang thanh toán.</li>
                                <li>Bạn cũng có thể nhập mã giảm giá ở trang thanh toán.</li>
                            </ul>

                            <button type="submit" id="btn-checkout" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-lg uppercase transition-colors shadow-lg shadow-red-600/30 disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed text-sm tracking-wide" disabled>
                                Thanh toán
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        @else
            <div class="bg-white rounded-xl shadow-sm py-20 flex flex-col items-center justify-center max-w-3xl mx-auto border border-gray-100">
                <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-gray-500 mb-6 font-medium">Chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="/" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-bold uppercase transition-colors shadow-lg shadow-red-600/20 text-sm">
                    Tiếp tục mua sắm
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const checkAll = document.getElementById('check-all');
    const totalEl = document.getElementById('total-price');
    const totalCountLabel = document.getElementById('total-count-label');
    const btnCheckout = document.getElementById('btn-checkout');

    // Hàm format tiền VNĐ
    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }

    // Tính tổng tiền & cập nhật số lượng đã chọn
    function calculateTotal() {
        let total = 0;
        let countChecked = 0;

        checkboxes.forEach(cb => {
            if(cb.checked) {
                let qty = document.querySelector(`.input-qty[data-id="${cb.dataset.id}"]`).value;
                total += parseFloat(cb.dataset.price) * parseInt(qty);
                countChecked++;
            }
        });
        
        totalEl.innerText = formatMoney(total);
        if (totalCountLabel) totalCountLabel.innerText = countChecked;

        // Bật/tắt nút mua hàng
        btnCheckout.disabled = (countChecked === 0);
        
        // Đồng bộ ô "Chọn tất cả"
        if (checkAll) {
            checkAll.checked = (countChecked === checkboxes.length && checkboxes.length > 0);
        }
    }

    // Bắt sự kiện Checkbox con
    checkboxes.forEach(cb => cb.addEventListener('change', calculateTotal));

    // Bắt sự kiện "Chọn tất cả"
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            calculateTotal();
        });
    }

    // Tăng giảm số lượng (Nút + / -)
    document.querySelectorAll('.btn-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.input-qty[data-id="${id}"]`);
            let qty = parseInt(input.value);

            if (this.dataset.action === 'plus') qty++;
            if (this.dataset.action === 'minus' && qty > 1) qty--;

            input.value = qty;
            updateQuantity(id, qty);
        });
    });

    // Nhập trực tiếp vào ô số lượng
    document.querySelectorAll('.input-qty').forEach(input => {
        input.addEventListener('change', function() {
            let qty = parseInt(this.value);
            if(qty < 1 || isNaN(qty)) { qty = 1; this.value = 1; }
            updateQuantity(this.dataset.id, qty);
        });
        // Chỉ cho phép nhập số
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    // Cập nhật lên Server
    function updateQuantity(id, qty) {
        calculateTotal(); // Cập nhật tổng tiền tức thì
        
        // Gọi AJAX lưu session
        fetch(`/cart/update/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: qty })
        });
    }

    // Nút Xóa hàng loạt
    const btnDeleteSelected = document.getElementById('btn-delete-selected');
    if (btnDeleteSelected) {
        btnDeleteSelected.addEventListener('click', async function() {
            const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
            
            if (selectedCheckboxes.length === 0) {
                alert('Vui lòng chọn ít nhất 1 sản phẩm để xóa!');
                return;
            }

            if (confirm(`Xóa ${selectedCheckboxes.length} sản phẩm đã chọn khỏi giỏ hàng?`)) {
                const originalText = this.innerHTML;
                this.innerHTML = "Đang xóa...";
                this.disabled = true;

                for (let cb of selectedCheckboxes) {
                    const id = cb.dataset.id;
                    try {
                        await fetch(`/cart/remove/${id}`); 
                    } catch (error) {
                        console.error("Lỗi xóa sản phẩm:", error);
                    }
                }
                window.location.reload();
            }
        });
    }

    // Chạy tính toán ngay khi vừa load trang
    calculateTotal();
});
</script>
@endsection