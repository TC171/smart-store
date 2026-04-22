@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">
        Chat với: {{ $user->name }}
    </h1>
</div>

<div class="bg-gray-900 rounded-xl p-6 h-[600px] overflow-y-auto space-y-4">

    @foreach($chats as $chat)

        @if($chat->sender == 'user')
            <!-- USER -->
            <div class="flex justify-end">
                <div class="bg-blue-500 text-white px-4 py-2 rounded-xl max-w-md">
                    {{ $chat->message }}
                    <div class="text-xs text-gray-200 mt-1 text-right">
                        {{ $chat->created_at }}
                    </div>
                </div>
            </div>
        @else
            <!-- AI -->
            <div class="flex justify-start">
                <div class="bg-gray-800 text-white px-4 py-2 rounded-xl max-w-md">
                    🤖 {{ $chat->message }}
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $chat->created_at }}
                    </div>
                </div>
            </div>
        @endif

    @endforeach

</div>

<div class="mt-4">
    {{ $chats->links() }}
</div>
@endsection