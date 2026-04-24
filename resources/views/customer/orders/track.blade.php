@extends('customer.layout')

@section('customer-content')

@php
$destAddr = collect([$order->shipping_address, $order->shipping_district, $order->shipping_city, $order->shipping_country])->filter()->implode(', ');
@endphp

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('customer.order.detail', $order) }}"
           class="text-gray-500 hover:text-gray-700 transition text-sm">← Quay lại đơn hàng</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-gray-800">
            Theo dõi đơn <span class="text-orange-500">#{{ $order->order_number }}</span>
        </h1>
    </div>

    {{-- Trạng thái --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4 flex items-center justify-between shadow-sm">
        <div>
            <p class="text-sm text-gray-500">Shipper: <span class="font-semibold text-gray-700">{{ $shipper?->name ?? 'Đang cập nhật' }}</span></p>
            <p class="text-sm text-gray-500 mt-0.5">Giao đến: <span class="text-gray-700">{{ $destAddr }}</span></p>
        </div>
        <span id="status-badge" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-600">
            Đang tải...
        </span>
    </div>

    {{-- Map --}}
    <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">
        <div id="map" style="height: 460px; width: 100%;"></div>
    </div>

    <p class="text-gray-400 text-xs mt-2 text-right" id="last-update">Chưa có dữ liệu</p>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const TRACK_URL  = "{{ route('customer.orders.track-data', $order) }}";
const DEST_ADDR  = @json($destAddr);
const REFRESH_MS = 15000;

const map = L.map('map').setView([16.047079, 108.206230], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

const shipperIcon = L.divIcon({ html: '<div style="font-size:26px">🚴</div>', iconSize:[32,32], iconAnchor:[16,16], className:'' });
const destIcon    = L.divIcon({ html: '<div style="font-size:26px">📍</div>', iconSize:[32,32], iconAnchor:[16,32], className:'' });

let shipperMarker = null, destMarker = null, routeLine = null;

async function geocodeDest() {
    try {
        const res  = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(DEST_ADDR)}&format=json&limit=1`, { headers: { 'Accept-Language': 'vi' } });
        const data = await res.json();
        if (data.length) {
            destMarker = L.marker([+data[0].lat, +data[0].lon], { icon: destIcon })
                .addTo(map)
                .bindPopup(`<b>📍 Địa chỉ nhận hàng</b><br>${DEST_ADDR}`);
        }
    } catch(e) {}
}

async function refresh() {
    try {
        const res  = await fetch(TRACK_URL);
        const data = await res.json();

        if (!data.available) {
            document.getElementById('status-badge').textContent = 'Chưa có tín hiệu GPS';
            return;
        }

        const lat = +data.latitude, lng = +data.longitude;

        if (shipperMarker) {
            shipperMarker.setLatLng([lat, lng]);
        } else {
            shipperMarker = L.marker([lat, lng], { icon: shipperIcon })
                .addTo(map)
                .bindPopup(`<b>🚴 Shipper đang trên đường</b><br>Cập nhật: ${data.location_updated_at}`);
            map.setView([lat, lng], 14);
        }
        shipperMarker.getPopup().setContent(`<b>🚴 Shipper đang trên đường</b><br>Cập nhật: ${data.location_updated_at}`);

        if (destMarker) {
            if (routeLine) map.removeLayer(routeLine);
            routeLine = L.polyline([[lat, lng], destMarker.getLatLng()], { color:'#f97316', weight:2, dashArray:'6,6' }).addTo(map);
        }

        document.getElementById('status-badge').textContent = '🟢 Đang giao';
        document.getElementById('status-badge').className   = 'inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600';
        document.getElementById('last-update').textContent  = 'Cập nhật: ' + new Date().toLocaleTimeString('vi-VN');

    } catch(e) {}
}

geocodeDest();
refresh();
setInterval(refresh, REFRESH_MS);
</script>

@endsection
