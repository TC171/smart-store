@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Quản lý đơn hàng</h1>
    </div>

    {{-- Tabs trạng thái --}}
    @php
        $tabs = [
            '' => 'Tất cả đơn hàng',
            'pending' => 'Đơn chờ xử lý',
            'shipping' => 'Đơn đang giao',
            'completed' => 'Đơn hoàn thành',
            'cancelled' => 'Đơn bị huỷ',
            'refunded' => 'Hoàn tiền',
        ];
        $currentStatus = request('status','');
    @endphp

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($tabs as $k => $label)
            <button type="button"
                class="px-3 py-2 rounded-lg text-sm border border-cyan-500/30 transition
                    {{ $currentStatus===$k ? 'bg-cyan-500/20 text-cyan-300' : 'bg-gray-900/60 text-gray-300 hover:bg-white/10' }}"
                onclick="setFilter('status','{{ $k }}')">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Filter row --}}
    <div class="bg-gray-900/70 rounded-xl shadow-lg p-4 mb-4 border border-white/5">
        <div class="flex flex-wrap gap-3 items-center">

            <input id="searchInput"
                class="bg-black/30 border border-cyan-500/20 text-white rounded-lg px-3 py-2 w-72 outline-none
                       focus:border-cyan-400/50"
                placeholder="Tìm mã đơn / tên / SĐT..."
                value="{{ request('q') }}"
                onkeyup="debouncedSearch()"
            />

            <select id="paymentStatusSelect"
                class="bg-black/30 border border-cyan-500/20 text-white rounded-lg px-3 py-2 outline-none
                       focus:border-cyan-400/50"
                onchange="setFilter('payment_status', this.value)">
                <option value="">Thanh toán</option>
                <option value="unpaid" {{ request('payment_status')=='unpaid'?'selected':'' }}>Chưa thanh toán</option>
                <option value="paid" {{ request('payment_status')=='paid'?'selected':'' }}>Đã thanh toán</option>
                <option value="refunded" {{ request('payment_status')=='refunded'?'selected':'' }}>Đã hoàn tiền</option>
            </select>

            <button type="button"
                class="px-3 py-2 rounded-lg bg-red-500/15 text-red-200 border border-red-500/30 hover:bg-red-500/25 transition"
                onclick="resetFilters()">
                Reset
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-gray-900/70 rounded-xl shadow-lg overflow-hidden border border-white/5">
        <div id="tableContainer">
            @include('admin.orders.partials.table', ['orders'=>$orders])
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4" id="paginationContainer">
        @include('admin.orders.partials.pagination', ['orders'=>$orders])
    </div>

</div>
@endsection

@push('scripts')
<script>
let filters = {
    status: @json(request('status','')),
    payment_status: @json(request('payment_status','')),
    q: @json(request('q','')),
    page: @json(request('page',''))
};

function setFilter(key, value){
    filters[key] = value;
    filters.page = ''; // đổi filter => về trang 1
    loadOrders();
}

function resetFilters(){
    filters = { status:'', payment_status:'', q:'', page:'' };
    document.getElementById('searchInput').value = '';
    document.getElementById('paymentStatusSelect').value = '';
    loadOrders();
}

let _timer = null;
function debouncedSearch(){
    clearTimeout(_timer);
    _timer = setTimeout(() => {
        filters.q = document.getElementById('searchInput').value;
        filters.page = '';
        loadOrders();
    }, 350);
}

function loadOrders() {
    const query = new URLSearchParams(filters).toString();

    fetch("{{ route('orders.index') }}?" + query, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(res => {
        document.getElementById('tableContainer').innerHTML = res.table;
        document.getElementById('paginationContainer').innerHTML = res.pagination;
    })
    .catch(err => console.error(err));
}

// bắt click pagination (AJAX)
document.addEventListener('click', function(e){
    const a = e.target.closest('a[data-page]');
    if(!a) return;
    e.preventDefault();
    filters.page = a.getAttribute('data-page');
    loadOrders();
});
</script>
@endpush