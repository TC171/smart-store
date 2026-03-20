<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

    // thêm điều kiện role = customer
    $credentials['role'] = 'customer';

    if (Auth::attempt($credentials)) {
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
