<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIAdminController extends Controller
{
    // =========================
    // 🔥 BẬT / TẮT AI
    // =========================
    public function settings()
    {
        $aiEnabled = DB::table('system_settings')
            ->where('setting_key', 'ai_enabled')
            ->value('setting_value') ?? '1';

        return view('admin.ai.settings', compact('aiEnabled'));
    }

    public function toggle()
    {
        $current = DB::table('system_settings')
            ->where('setting_key', 'ai_enabled')
            ->value('setting_value') ?? '1';

        $newValue = $current == '1' ? '0' : '1';

        DB::table('system_settings')->updateOrInsert(
            ['setting_key' => 'ai_enabled'],
            [
                'setting_value' => $newValue,
                'updated_at' => now()
            ]
        );

        return back()->with('success', 'Cập nhật trạng thái AI thành công');
    }

    // =========================
    // 🔥 DANH SÁCH USER ĐÃ CHAT
    // =========================
    public function users()
    {
        $users = DB::table('chats')
            ->join('users', 'users.id', '=', 'chats.user_id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('MAX(chats.created_at) as last_chat'),
                DB::raw('COUNT(chats.id) as total_messages')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('last_chat')
            ->get();

        return view('admin.ai.users', compact('users'));
    }

    // =========================
    // 🔥 CHI TIẾT CHAT 1 USER
    // =========================
    public function detail($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            abort(404, 'User không tồn tại');
        }

        $chats = DB::table('chats')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return view('admin.ai.detail', compact('chats', 'user'));
    }
}