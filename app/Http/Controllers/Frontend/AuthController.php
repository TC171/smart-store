<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ===== UI =====
    public function showLogin()
    {
        return view('frontend.auth.login');
    }

    public function showRegister()
    {
        return view('frontend.auth.register');
    }

    // ===== LOGIN =====
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {

            $request->session()->regenerate(); // ⚠️ bắt buộc

            $user = Auth::guard('web')->user();

            if ($user->role !== 'customer') {
                Auth::guard('web')->logout();

                return back()->with('error', 'Không đúng quyền');
            }

            return redirect()->route('home');
        }

        return back()->with('error', 'Sai tài khoản hoặc mật khẩu');
    }

    // ===== REGISTER =====
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // 🔥 bắt buộc
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công');
    }

    // ===== LOGOUT =====
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->forget('login_web');

        return redirect('/');
    }
}
