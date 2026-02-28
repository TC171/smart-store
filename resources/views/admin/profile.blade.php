@extends('admin.layouts.app')

@section('content')

<div class="max-w-2xl bg-white/5 backdrop-blur-xl
            border border-purple-500/30
            p-8 rounded-2xl">

    <h2 class="text-2xl font-bold mb-6">Profile</h2>

    <div class="space-y-4">
        <div>
            <label class="text-gray-400">Name</label>
            <p class="mt-1">{{ auth()->user()->name }}</p>
        </div>

        <div>
            <label class="text-gray-400">Email</label>
            <p class="mt-1">{{ auth()->user()->email }}</p>
        </div>
    </div>

</div>

@endsection