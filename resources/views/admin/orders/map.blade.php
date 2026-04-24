@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.orders.show', $order) }}"
           class="text-gray-400 hover:text-white transition">← Đơn hàng</a>
        <h1 class="text-xl font-bold text-white">
            🗺️ Theo dõi đơn <span class="text-cyan-400">#{{ $order->order_number }}</span>
        </h1>
        <span id="status-badge" class="ml-auto inline-block px-3 py-1 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-400">
            Đang tải vị trí...
        </span>
    </div>

    {{-- Thông tin nhanh --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-gray-400 text-xs uppercase tracking-wider mb-1">Shipper</div>
            <div class="text-white font-semibold">{{ $shipper?->name ?? 'Chưa phân công' }}</div>
            <div class="text-gray-400 text-sm">{{ $shipper?->phone ?? '' }}</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-gray-400 text-xs uppercase tracking-wider mb-1">Người nhận</div>
            <div class="text-white font-semibold">{{ $order->shipping_name }}</div>
            <div class="text-gray-400 text-sm">{{ $order->shipping_phone }}</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-gray-400 text-xs uppercase tracking-wider mb-1">Địa chỉ giao</div>
            <div class="text-white text-sm leading-snug">
                {{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}
            </div>
        </div>
    </div>

    {{-- Map --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div id="map" style="height: 520px; width: 100%;"></div>
    </div>

    <p class="text-gray-500 text-xs mt-3 text-right" id="last-update">Chưa cập nhật</p>

</div>

@php
$destAddress = collect([$order->shipping_address, $order->shipping_district, $order->shipping_city, $order->shipping_country])->filter()->implode(', ');
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const LOCATION_URL = "{{ route('admin.orders.shipper-location', $order) }}";
const DEST_ADDRESS = @json($destAddress);
const REFRESH_MS   = 15000;

const map = L.map('map').setView([16.047079, 108.206230], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

const shipperIcon = L.divIcon({
    html: '<div style="font-size:28px;line-height:1;">🚴</div>',
    iconSize: [32, 32], iconAnchor: [16, 16], className: '',
});
const destIcon = L.divIcon({
    html: '<div style="font-size:28px;line-height:1;">📍</div>',
    iconSize: [32, 32], iconAnchor: [16, 32], className: '',
});

let shipperMarker = null, destMarker = null, routeLine = null;

async function geocodeDestination() {
    try {
        const res  = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(DEST_ADDRESS)}&format=json&limit=1`, { headers: { 'Accept-Language': 'vi' } });
        const data = await res.json();
        if (data.length > 0) {
            destMarker = L.marker([+data[0].lat, +data[0].lon], { icon: destIcon })
                .addTo(map)
                .bindPopup(`<b>📍 Điểm giao hàng</b><br>${DEST_ADDRESS}`);
        }
    } catch (e) {}
}

async function refreshShipper() {
    try {
        const res  = await fetch(LOCATION_URL);
        const data = await res.json();

        if (!data.available) {
            document.getElementById('status-badge').textContent = 'Chưa có tín hiệu GPS';
            document.getElementById('status-badge').className   = 'ml-auto inline-block px-3 py-1 rounded-full text-xs font-bold bg-gray-500/20 text-gray-400';
            return;
        }

        const lat = parseFloat(data.latitude);
        const lng = parseFloat(data.longitude);

        if (shipperMarker) {
            shipperMarker.setLatLng([lat, lng]);
        } else {
            shipperMarker = L.marker([lat, lng], { icon: shipperIcon })
                .addTo(map)
                .bindPopup(`<b>🚴 ${data.name}</b><br>Cập nhật: ${data.location_updated_at}`);
            map.setView([lat, lng], 14);
        }
        shipperMarker.getPopup().setContent(`<b>🚴 ${data.name}</b><br>Cập nhật: ${data.location_updated_at}`);

        if (destMarker) {
            if (routeLine) map.removeLayer(routeLine);
            routeLine = L.polyline([[lat, lng], destMarker.getLatLng()], { color: '#22d3ee', weight: 2, dashArray: '6,6' }).addTo(map);
        }

        document.getElementById('status-badge').textContent = '🟢 Đang cập nhật';
        document.getElementById('status-badge').className   = 'ml-auto inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400';
        document.getElementById('last-update').textContent  = 'Cập nhật lần cuối: ' + new Date().toLocaleTimeString('vi-VN');

    } catch (e) {
        document.getElementById('status-badge').textContent = 'Lỗi kết nối';
    }
}

geocodeDestination();
refreshShipper();
setInterval(refreshShipper, REFRESH_MS);
</script>

@endsection


