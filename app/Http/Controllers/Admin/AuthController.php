<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            $request->session()->regenerate(); // ⚠️ bắt buộc

            $user = Auth::guard('admin')->user();

            if (! in_array($user->role, ['admin', 'staff'])) {
                Auth::guard('admin')->logout();

                return back()->with('error', 'Không đúng quyền admin');
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Sai tài khoản hoặc mật khẩu');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->forget('login_admin'); // hoặc key riêng

        return redirect()->route('admin.login');
    }
}
