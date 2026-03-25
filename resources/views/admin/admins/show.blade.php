@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Chi tiet quan tri vien</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><strong>Ten:</strong> {{ $admin->name }}</div>
            <div><strong>Email:</strong> {{ $admin->email }}</div>
            <div><strong>Vai tro:</strong> {{ strtoupper($admin->role) }}</div>
            <div><strong>Trang thai:</strong> {{ $admin->status ? 'Hoat dong' : 'Tam khoa' }}</div>
            <div><strong>So dien thoai:</strong> {{ $admin->phone ?? '-' }}</div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.admins.edit', $admin) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Chinh sua</a>
            <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Quay lai</a>
        </div>
    </div>
</div>
@endsection
