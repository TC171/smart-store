@extends('admin.layouts.app')

@section('content')

<div class="max-w-2xl bg-white/5 backdrop-blur-xl
            border border-purple-500/30
            p-8 rounded-2xl">

    <h2 class="text-2xl font-bold mb-6">Change Password</h2>

    <form method="POST" action="#">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-400 mb-2">New Password</label>
            <input type="password"
                   class="w-full p-3 bg-black/50 border border-purple-500/30 rounded-xl">
        </div>

        <button type="submit"
                class="px-6 py-3 bg-purple-600 rounded-xl
                       hover:bg-purple-700 transition">
            Update
        </button>
    </form>

</div>

@endsection