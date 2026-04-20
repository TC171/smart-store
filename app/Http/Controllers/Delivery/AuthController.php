<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('delivery.auth.login');
    }

    public function showRegister()
    {
        return view('delivery.auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('delivery')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard('delivery')->user();
            if ($user->role !== 'shipper') {
                Auth::guard('delivery')->logout();
                return back()->with('error', 'Tài khoản này không phải tài khoản giao hàng.');
            }

            return redirect()->route('delivery.dashboard');
        }

        return back()->with('error', 'Sai email hoặc mật khẩu.');
    }

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
            'role' => 'shipper',
        ]);

        return redirect()->route('delivery.login')->with('success', 'Đăng ký thành công. Vui lòng đăng nhập.');
    }

    public function logout(Request $request)
    {
        Auth::guard('delivery')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('delivery.login');
    }
}
