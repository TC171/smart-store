@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Quản lý đơn hàng</h1>
    </div>

    @php
        $tabs = [
            '' => 'Tất cả đơn hàng',
            'pending' => 'Đơn chờ xử lý',
            'shipping' => 'Đơn đang giao',
            'completed' => 'Đơn hoàn thành',
            'cancelled' => 'Đơn bị huỷ',
            'refunded' => 'Hoàn tiền',
        ];
        $currentStatus = request('status', '');
    @endphp

    {{-- Tabs trạng thái --}}
    <div class="flex flex-wrap gap-2 mb-4" id="statusTabs">
        @foreach($tabs as $key => $label)
            <button
                type="button"
                data-status="{{ $key }}"
                class="tabStatus px-3 py-2 rounded-lg text-sm border border-cyan-500/30
                {{ $currentStatus === $key ? 'bg-cyan-500/20 text-cyan-300' : 'bg-gray-900/70 text-gray-300 hover:bg-white/10' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-gray-900/70 rounded-xl shadow-lg p-4 mb-4 border border-cyan-500/10">
        <div class="flex flex-wrap gap-3 items-center">
            <input id="searchInput"
                class="bg-black/30 border border-cyan-500/20 text-white rounded-lg px-3 py-2 w-72 outline-none"
                placeholder="Tìm mã đơn / tên / SĐT..."
                value="{{ request('q') }}">

            <select id="paymentStatusSelect"
                class="bg-black/30 border border-cyan-500/20 text-white rounded-lg px-3 py-2">
                <option value="">Thanh toán</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>

            <button id="resetBtn"
                class="px-3 py-2 rounded-lg bg-red-500/20 text-red-300 border border-red-500/30 hover:bg-red-500/30 transition">
                Reset
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-gray-900/70 rounded-xl shadow-lg overflow-hidden border border-cyan-500/10">
        <div id="tableContainer">
            @include('admin.orders.partials.table', ['orders' => $orders])
        </div>
    </div>

    {{-- Pagination --}}
    <div id="paginationContainer" class="mt-4">
        @include('admin.orders.partials.pagination', ['orders' => $orders])
    </div>

</div>
@endsection

@push('scripts')
<script>
let filters = {
    status: @json(request('status', '')),
    payment_status: @json(request('payment_status', '')),
    q: @json(request('q', '')),
    page: @json(request('page', ''))
};

function loadOrders() {
    const params = new URLSearchParams();

    Object.keys(filters).forEach(key => {
        if (filters[key] !== null && filters[key] !== '') {
            params.append(key, filters[key]);
        }
    });

    fetch("{{ route('orders.index') }}?" + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('tableContainer').innerHTML = data.table;
        document.getElementById('paginationContainer').innerHTML = data.pagination;
        highlightActiveTabs();
    })
    .catch(err => console.error(err));
}

function highlightActiveTabs() {
    document.querySelectorAll('.tabStatus').forEach(btn => {
        const status = btn.dataset.status;

        if (status === filters.status) {
            btn.classList.add('bg-cyan-500/20', 'text-cyan-300');
            btn.classList.remove('bg-gray-900/70', 'text-gray-300');
        } else {
            btn.classList.remove('bg-cyan-500/20', 'text-cyan-300');
            btn.classList.add('bg-gray-900/70', 'text-gray-300');
        }
    });
}

// click tab status
document.addEventListener('click', function(e) {
    const tab = e.target.closest('.tabStatus');
    if (tab) {
        filters.status = tab.dataset.status ?? '';
        filters.page = '';
        loadOrders();
        return;
    }

    const pageLink = e.target.closest('a[data-page]');
    if (pageLink) {
        e.preventDefault();
        filters.page = pageLink.dataset.page;
        loadOrders();
        return;
    }
});

// search debounce
let timer = null;
document.getElementById('searchInput').addEventListener('keyup', function() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        filters.q = this.value;
        filters.page = '';
        loadOrders();
    }, 300);
});

// payment filter
document.getElementById('paymentStatusSelect').addEventListener('change', function() {
    filters.payment_status = this.value;
    filters.page = '';
    loadOrders();
});

// reset
document.getElementById('resetBtn').addEventListener('click', function() {
    filters = {
        status: '',
        payment_status: '',
        q: '',
        page: ''
    };

    document.getElementById('searchInput').value = '';
    document.getElementById('paymentStatusSelect').value = '';

    loadOrders();
});
</script>
@endpush