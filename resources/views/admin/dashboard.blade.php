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
                ['label' => 'Revenue', 'value' => 12450, 'color' => 'text-green-400'],
                ['label' => 'Orders', 'value' => 320, 'color' => 'text-blue-400'],
                ['label' => 'Customers', 'value' => 1240, 'color' => 'text-pink-400'],
                ['label' => 'Products', 'value' => 85, 'color' => 'text-yellow-400'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white/5 backdrop-blur-xl border border-purple-500/30
                    p-6 rounded-2xl shadow-lg hover:scale-105 transition
                    hover:shadow-[0_0_30px_#9333ea]">

            <p class="text-gray-400 text-sm">{{ $stat['label'] }}</p>
            <h2 class="text-3xl font-bold mt-3 {{ $stat['color'] }} counter"
                data-target="{{ $stat['value'] }}">
                0
            </h2>
        </div>
        @endforeach

    </div>

    <!-- CHART -->
    <div class="bg-white/5 backdrop-blur-xl border border-indigo-500/30
                p-8 rounded-2xl shadow-xl">

        <h2 class="text-xl font-semibold mb-6">
            Revenue Analytics
        </h2>

        <canvas id="chart"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// COUNTER ANIMATION
document.querySelectorAll('.counter').forEach(counter => {
    let target = +counter.getAttribute('data-target');
    let count = 0;
    let increment = target / 100;

    let update = () => {
        if (count < target) {
            count += increment;
            counter.innerText = Math.floor(count);
            requestAnimationFrame(update);
        } else {
            counter.innerText = target;
        }
    };

    update();
});

// CHART
const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            data: [1200,1900,3000,2500,4000,5200],
            borderColor: '#9333ea',
            backgroundColor: 'rgba(147,51,234,0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        plugins: { legend: { display:false }},
        scales: {
            x: { ticks:{ color:'#9ca3af' }},
            y: { ticks:{ color:'#9ca3af' }}
        }
    }
});
</script>

@endsection