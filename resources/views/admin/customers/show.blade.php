@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Chi tiet khach hang</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><strong>Ten:</strong> {{ $customer->name }}</div>
            <div><strong>Email:</strong> {{ $customer->email }}</div>
            <div><strong>So dien thoai:</strong> {{ $customer->phone ?? '-' }}</div>
            <div><strong>Trang thai:</strong> {{ $customer->status ? 'Hoat dong' : 'Tam khoa' }}</div>
            <div><strong>Gioi tinh:</strong> {{ $customer->gender ?? '-' }}</div>
            <div><strong>Ngay sinh:</strong> {{ $customer->date_of_birth ?? '-' }}</div>
            <div class="md:col-span-2"><strong>Dia chi:</strong> {{ $customer->address ?? '-' }}</div>
            <div><strong>Thanh pho:</strong> {{ $customer->city ?? '-' }}</div>
            <div><strong>Quoc gia:</strong> {{ $customer->country ?? '-' }}</div>
            <div><strong>Ma buu chinh:</strong> {{ $customer->postal_code ?? '-' }}</div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Chinh sua</a>
            <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Quay lai</a>
        </div>
    </div>
</div>
@endsection
