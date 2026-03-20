@extends('admin.layouts.app')

@section('content')

<div class="space-y-12">

    <div>
        <h1 class="text-4xl font-bold tracking-wider">
            CYBER DASHBOARD
        </h1>
        <p class="text-gray-400 mt-2">
            Welcome back, {{ auth()->user()->name }}
        </p>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        @php
            $stats = [
                ['label' => 'Doanh Thu', 'value' => number_format($totalRevenue ?? 0, 0, ',', '.'), 'color' => 'text-green-400', 'icon' => '₫'],
                ['label' => 'Đơn Hàng', 'value' => number_format($totalOrders ?? 0), 'color' => 'text-blue-400', 'icon' => 'orders'],
                ['label' => 'Khách Hàng', 'value' => number_format($totalCustomers ?? 0), 'color' => 'text-pink-400', 'icon' => 'users'],
                ['label' => 'Sản Phẩm', 'value' => number_format($totalProducts ?? 0), 'color' => 'text-yellow-400', 'icon' => 'products'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white/5 backdrop-blur-xl border border-purple-500/30
                    p-6 rounded-2xl shadow-lg hover:scale-105 transition
                    hover:shadow-[0_0_30px_#9333ea]">
            <p class="text-gray-400 text-sm uppercase tracking-wide">{{ $stat['label'] }}</p>
            <div class="flex items-baseline gap-2 mt-3">
                <h2 class="text-3xl font-bold {{ $stat['color'] }}">
                    {{ $stat['value'] }}
                </h2>
                @if($stat['icon'] == '₫')<span class="text-sm text-gray-400">₫</span>@endif
            </div>
        </div>
        @endforeach

    </div>

    <!-- RECENT ORDERS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- CHART -->
        <div class="bg-white/5 backdrop-blur-xl border border-indigo-500/30
                    p-8 rounded-2xl shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Doanh Thu Theo Tháng</h2>
                <span class="text-sm text-gray-400">{{ now()->year }}</span>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        <!-- RECENT ORDERS -->
        <div class="bg-white/5 backdrop-blur-xl border border-emerald-500/30
                    p-8 rounded-2xl shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Đơn Hàng Gần Đây</h2>
                <a href="{{ route('orders.index') }}" class="text-purple-400 hover:text-purple-300 text-sm">Xem tất cả →</a>
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-4 bg-white/10 rounded-xl border-l-4
                           {{ $order->status == 'completed' ? 'border-emerald-400' : ($order->status == 'shipping' ? 'border-blue-400' : ($order->status == 'cancelled' ? 'border-red-400' : 'border-yellow-400')) }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r {{ $order->status == 'completed' ? 'from-emerald-500 to-emerald-600' : ($order->status == 'shipping' ? 'from-blue-500 to-blue-600' : ($order->status == 'cancelled' ? 'from-red-500 to-red-600' : 'from-yellow-500 to-yellow-600')) }}
                                   rounded-lg flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($order->status, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-white">#{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-300">{{ $order->user->name ?? 'Khách' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg text-white">
                            {{ number_format($order->grand_total, 0, ',', '.') }}₫
                        </p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('d/m H:i') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400">
                    <p>Chưa có đơn hàng nào</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// DYNAMIC CHART DATA
const months = ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'];
const monthlyRevenue = @json($monthlyRevenue ?? []);
const chartData = months.map((month, index) => monthlyRevenue[index+1] || 0);

const ctx = document.getElementById('revenueChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                data: chartData,
                borderColor: '#9333ea',
                backgroundColor: 'rgba(147,51,234,0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#9333ea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: { color: '#9ca3af', maxRotation: 0 },
                    grid: { color: 'rgba(147,51,234,0.1)' }
                },
                y: {
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) { return this.formatNumber(value) + '₫'; }
                    },
                    grid: { color: 'rgba(147,51,234,0.1)' }
                }
            }
        }
    });
}
</script>
</script>

@endsection
