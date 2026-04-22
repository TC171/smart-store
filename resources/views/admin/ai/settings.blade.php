@extends('admin.layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-white">Quản lý AI</h1>

    <form method="POST" action="{{ route('admin.ai.toggle') }}">
        @csrf

        <button type="submit"
    class="{{ $aiEnabled == '1' 
        ? 'bg-green-500 hover:bg-green-600' 
        : 'bg-red-500 hover:bg-red-600' }}
    text-black px-4 py-2 rounded-lg font-semibold">

    {{ $aiEnabled == '1' ? '🟢 AI đang bật (Click để tắt)' : '🔴 AI đang tắt (Click để bật)' }}
</button>
    </form>
</div>

<div class="bg-gray-900 rounded-xl p-6 text-white">
    <p>• Trạng thái AI: 
        <b class="{{ $aiEnabled == '1' ? 'text-green-400' : 'text-red-400' }}">
            {{ $aiEnabled == '1' ? 'ĐANG BẬT' : 'ĐANG TẮT' }}
        </b>
    </p>

    <p class="mt-2 text-gray-400 text-sm">
        Khi tắt AI, chatbot frontend sẽ không phản hồi người dùng.
    </p>
</div>
@endsection