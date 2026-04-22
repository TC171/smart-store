@extends('admin.layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-white">Khách đã chat với AI</h1>
</div>

<div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden">

    <table class="w-full text-white">
        <thead class="bg-gray-800 text-gray-300">
            <tr>
                <th class="p-4 text-left">User</th>
                <th class="p-4 text-left">Chat gần nhất</th>
                <th class="p-4 text-center">Hành động</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $u)
                <tr class="border-t border-gray-800 hover:bg-gray-800/50">
                    <td class="p-4">{{ $u->name }}</td>
                    <td class="p-4 text-gray-400">{{ $u->last_chat }}</td>
                    <td class="p-4 text-center">
                        <a href="{{ route('admin.ai.detail', $u->id) }}"
                           class="bg-purple-500 hover:bg-purple-600 px-4 py-2 rounded-lg text-black">
                            Xem chat
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-400">
                        Chưa có dữ liệu
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection