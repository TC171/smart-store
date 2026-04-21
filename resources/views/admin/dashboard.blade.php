@extends('admin.layouts.app')

@section('content')

<div class="space-y-10">

<!-- HEADER -->
<div>
    <h1 class="text-4xl font-bold tracking-wider">
        CYBER DASHBOARD
    </h1>
    <p class="text-gray-400 mt-2">
        Welcome back, {{ auth()->user()->name }}
    </p>
</div>

<!-- KPI -->
<!-- KPI SAAS -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <!-- REVENUE -->
    <div class="relative p-6 rounded-3xl bg-gradient-to-br from-emerald-500/10 to-emerald-900/10 border border-emerald-400/20 backdrop-blur-xl hover:scale-[1.02] transition">

        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Doanh thu</p>
                <h2 class="text-3xl font-bold text-emerald-400 mt-2">
                    {{ number_format($totalRevenue) }}₫
                </h2>
            </div>

            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                💰
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-400 space-y-1">
            <p>Hôm nay: <span class="text-white font-semibold">
                {{ number_format($todayRevenue ?? 0) }}₫
            </span></p>

            <p>TB / đơn: 
                <span class="text-white font-semibold">
                    {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders) : 0 }}₫
                </span>
            </p>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-transparent rounded-b-3xl"></div>
    </div>


    <!-- ORDERS -->
    <div class="relative p-6 rounded-3xl bg-gradient-to-br from-blue-500/10 to-blue-900/10 border border-blue-400/20 backdrop-blur-xl hover:scale-[1.02] transition">

        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Đơn hàng</p>
                <h2 class="text-3xl font-bold text-blue-400 mt-2">
                    {{ number_format($totalOrders) }}
                </h2>
            </div>

            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                📦
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-400 space-y-1">
            <p>Hôm nay: 
                <span class="text-white font-semibold">
                    {{ number_format($todayOrders ?? 0) }}
                </span>
            </p>

            <p>Tỷ lệ / khách:
                <span class="text-white font-semibold">
                    {{ $totalCustomers > 0 ? round($totalOrders / $totalCustomers, 2) : 0 }}
                </span>
            </p>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-transparent rounded-b-3xl"></div>
    </div>


    <!-- CUSTOMERS -->
    <div class="relative p-6 rounded-3xl bg-gradient-to-br from-pink-500/10 to-pink-900/10 border border-pink-400/20 backdrop-blur-xl hover:scale-[1.02] transition">

        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Khách hàng</p>
                <h2 class="text-3xl font-bold text-pink-400 mt-2">
                    {{ number_format($totalCustomers) }}
                </h2>
            </div>

            <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center">
                👤
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-400 space-y-1">
            <p>Mới hôm nay:
                <span class="text-white font-semibold">
                    {{ number_format($todayCustomers ?? 0) }}
                </span>
            </p>

            <p>Giá trị TB:
                <span class="text-white font-semibold">
                    {{ $totalCustomers > 0 ? number_format($totalRevenue / $totalCustomers) : 0 }}₫
                </span>
            </p>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-pink-400 to-transparent rounded-b-3xl"></div>
    </div>


    <!-- PRODUCTS -->
    <div class="relative p-6 rounded-3xl bg-gradient-to-br from-yellow-500/10 to-yellow-900/10 border border-yellow-400/20 backdrop-blur-xl hover:scale-[1.02] transition">

        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Sản phẩm</p>
                <h2 class="text-3xl font-bold text-yellow-400 mt-2">
                    {{ number_format($totalProducts) }}
                </h2>
            </div>

            <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                🛒
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-400 space-y-1">
            <p>Bán hôm nay:
                <span class="text-white font-semibold">
                    {{ number_format($todayProducts ?? 0) }}
                </span>
            </p>

            Hiệu suất (Doanh thu/ Sản Phẩm):
<span class="text-white font-semibold">
    {{ $totalProducts > 0 
        ? number_format($totalRevenue / $totalProducts) 
        : 0 }}₫
</span>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 to-transparent rounded-b-3xl"></div>
    </div>

</div>

<!-- MAIN -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

<!-- CHART -->
<div id="chartBox" class="lg:col-span-2 p-6 rounded-3xl bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] shadow-2xl relative">

    <div class="flex justify-between items-center mb-4">
        
        <div>
            <h2 class="text-xl font-bold" id="chartTitle"></h2>
            <p class="text-xs text-gray-400">Tổng trong khoảng</p>
            <p id="chartTotal" class="text-2xl text-green-400 font-bold mt-1">0₫</p>
        </div>

        <span class="text-sm text-gray-400">{{ now()->year }}</span>
    </div>

    <!-- BUTTON FIXED UI -->
    <button id="toggleFullscreen"
        class="absolute top-4 right-4 px-3 py-1 text-xs rounded-lg bg-white/10 hover:bg-white/20 transition backdrop-blur">
        ⛶ Phóng to
    </button>

    <!-- FILTER -->
    <div class="flex gap-3 mb-5 flex-wrap">

        <!-- group -->
        <select id="groupBy" class="bg-black/40 border rounded px-3 py-2 text-sm">
            <option value="day">Theo ngày</option>
            <option value="week">Theo tuần</option>
            <option value="month" selected>Theo tháng</option>
            <option value="quarter">Theo quý</option>
            <option value="year">Theo năm</option>
        </select>

        <!-- range -->
        <input type="date" id="fromDate"
            value="{{ now()->startOfMonth()->format('Y-m-d') }}"
            class="bg-black/40 border rounded px-3 py-2 text-sm">

        <input type="date" id="toDate"
            value="{{ now()->format('Y-m-d') }}"
            class="bg-black/40 border rounded px-3 py-2 text-sm">

        <!-- preset -->
        <button onclick="setRange(7)" class="px-3 py-2 bg-white/10 rounded text-xs">7 ngày</button>
        <button onclick="setRange(30)" class="px-3 py-2 bg-white/10 rounded text-xs">30 ngày</button>
        <button onclick="setRange('month')" class="px-3 py-2 bg-white/10 rounded text-xs">Tháng này</button>

        <button onclick="loadChart()"
            class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded text-sm">
            Lọc
        </button>
    </div>

 <div class="h-[420px] overflow-x-auto">
    <div id="chartWrapper" class="min-w-[1200px] h-full">
       <canvas id="revenueChart" class="w-full h-full"></canvas>
    </div>
</div>

</div>

<!-- ORDERS giữ nguyên -->
<div class="p-6 rounded-3xl bg-white/5 border border-emerald-500/20">
    <div class="flex justify-between mb-4">
        <h2 class="font-semibold">Đơn gần đây</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-purple-400 text-sm">Xem →</a>
    </div>

    <div class="space-y-3 max-h-[420px] overflow-y-auto">
        @forelse($recentOrders as $order)
        <div class="flex justify-between p-3 bg-white/10 rounded-xl">
            <div>
                <p class="font-semibold">#{{ $order->order_number }}</p>
                <p class="text-xs text-gray-400">{{ $order->user->name ?? 'Khách' }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold">{{ number_format($order->grand_total) }}₫</p>
                <p class="text-xs text-gray-400">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
        @empty
        <p class="text-center text-gray-400">Chưa có đơn</p>
        @endforelse
    </div>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1"></script>

<script>
let chart;
let currentMetric = 'revenue';

function formatMoney(v){
    return v.toLocaleString('vi-VN') + ' ₫';
}

function setLoading(state){
    const btn = document.querySelector('button[onclick="loadChart()"]');
    if(state){
        btn.innerText = 'Đang tải...';
        btn.disabled = true;
    }else{
        btn.innerText = 'Lọc';
        btn.disabled = false;
    }
}

function setRange(type){
    const now = new Date();
    let from = new Date(), to = new Date();

    if(type === 7){
        from.setDate(now.getDate() - 7);
    }

    if(type === 30){
        from.setDate(now.getDate() - 30);
    }

    if(type === 'month'){
        from = new Date(now.getFullYear(), now.getMonth(), 1);
    }

    document.getElementById('fromDate').value = from.toISOString().slice(0,10);
    document.getElementById('toDate').value = to.toISOString().slice(0,10);

    loadChart();
}

// ===== MAIN =====
async function loadChart(){
  
    

    try{
        setLoading(true);

        const type = document.getElementById('groupBy').value;
        const from = document.getElementById('fromDate').value;
        const to = document.getElementById('toDate').value;

        const url = `{{ route('admin.revenue.chart') }}?type=${type}&from=${from}&to=${to}&metric=${currentMetric}`;

        const res = await fetch(url);
        const data = await res.json();

        const labels = data.map(i => i.label);
          const values = data.map(i => Number(i.total));

const wrapper = document.getElementById('chartWrapper');
const minWidth = Math.max(labels.length * 60, 1200);
wrapper.style.width = minWidth + 'px';


        const total = values.reduce((a,b)=>a+b,0);

        document.getElementById('chartTotal').innerText =
            currentMetric === 'revenue'
                ? formatMoney(total)
                : total.toLocaleString('vi-VN');

        let max = Math.max(...values);
        let min = Math.min(...values);

        document.getElementById('chartTitle').innerText =
            `Doanh Thu cao nhất: ${currentMetric === 'revenue' ? formatMoney(max) : max} 
             Doanh Thu thấp nhất: ${currentMetric === 'revenue' ? formatMoney(min) : min}`;

        if(chart) chart.destroy();
const canvas = document.getElementById('revenueChart');
const ctx = canvas.getContext('2d');

        chart = new Chart(ctx, {
            type: 'bar', // 🔥 CHUYỂN SANG BAR CHART
            data: {
                labels: labels,
                datasets: [{
    label: currentMetric === 'revenue' ? 'Doanh thu' : 'Đơn hàng',
    data: values,

    backgroundColor: 'rgba(168,85,247,0.6)',
    borderColor: 'rgba(168,85,247,1)',
    borderWidth: 1,

    borderRadius: 6,
    maxBarThickness: 30,

    categoryPercentage: 0.6,
    barPercentage: 0.8
}]
            },
            

            options: {
                responsive: true,
                maintainAspectRatio: false,
    layout: {
        padding: 10
         },
                plugins: {

                    legend: {
                        labels: { color: '#ccc' }
                    },

                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                return currentMetric === 'revenue'
                                    ? formatMoney(ctx.raw)
                                    : ctx.raw;
                            }
                        }
                    }
                },

              scales: {

   x: {
    offset: true,

    grid: {
        display: false
    },

    ticks: {
        color: '#aaa',

        autoSkip: false, // giữ tất cả label
        maxRotation: 0,
        minRotation: 0,
        padding: 14,

        // 🔥 FIX QUAN TRỌNG: ép label xuống dòng
        callback: function(value) {
            const label = this.getLabelForValue(value);

            // chuyển 2026-04-22 → xuống dòng
            const parts = label.split('-');
            return parts; 
        }
    }
},

    y: {
        ticks: {
            color: '#aaa',
            callback: (v) =>
                currentMetric === 'revenue'
                    ? v.toLocaleString('vi-VN')
                    : v
        },

        grid: {
            color: 'rgba(255,255,255,0.05)'
        }
    }
}
            }
        });

    }catch(e){
        console.error(e);
    }finally{
        setLoading(false);
    }
}

document.addEventListener("DOMContentLoaded", loadChart);




const btn = document.getElementById('toggleFullscreen');
const box = document.getElementById('chartBox');

function toggleFullscreen() {
    const isFull = box.classList.toggle('fullscreen');

    // khóa scroll nền
    document.body.style.overflow = isFull ? 'hidden' : '';

    // đổi text button
    btn.innerText = isFull ? '❐ Thu nhỏ' : '⛶ Phóng to';

    // resize chart kiểu trading
    setTimeout(() => {
        if (chart) {
            chart.resize();
            chart.update('none');
        }
    }, 80);
}

// click
btn.addEventListener('click', toggleFullscreen);

// ESC để thoát fullscreen (TradingView style)
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && box.classList.contains('fullscreen')) {
        toggleFullscreen();
    }
});


document.addEventListener('click', (e) => {
    if (box.classList.contains('fullscreen')) {
        if (!box.contains(e.target) && e.target !== btn) {
            toggleFullscreen();
        }
    }
});
</script>
@endsection
<style>
#chartBox.fullscreen {
    position: fixed;
    inset: 0;
    z-index: 9999;

    background: rgba(10, 10, 20, 0.85);
    backdrop-filter: blur(14px);

    display: flex;
    flex-direction: column;

    padding: 16px;
    overflow: hidden;
    border-radius: 0;

    animation: tvZoom 0.2s ease;
}

@keyframes tvZoom {
    from {
        transform: scale(0.98);
        opacity: 0.6;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

#chartBox.fullscreen #chartWrapper {
    flex: 1;
    min-height: 0;
    width: 100% !important;
}

#chartBox.fullscreen canvas {
    width: 100% !important;
    height: 100% !important;
}
</style>