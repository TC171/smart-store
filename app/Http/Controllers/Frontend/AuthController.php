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

    // ===== FORGOT PASSWORD =====
    public function showForgotPassword()
    {
        return view('frontend.auth.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->where('role', 'customer')->first();

        if (!$user) {
            return back()->with('error', 'Email không tồn tại trên hệ thống');
        }

        // Generate an 8-character random string for the new password
        $newPassword = \Illuminate\Support\Str::random(8);

        // Update password in db
        $user->password = Hash::make($newPassword);
        $user->save();

        // Send email with the new password
        \Illuminate\Support\Facades\Mail::send('emails.forgot_password', ['newPassword' => $newPassword], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Mật khẩu mới của bạn - Smart Store');
        });

        return redirect()->route('customer.login')->with('success', 'Mật khẩu mới đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.');
    }

    // ===== LOGIN =====
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {

            $request->session()->regenerate(); // ⚠️ bắt buộc

            $user = Auth::guard('web')->user();

            // Redirect based on role
            if ($user->role === 'customer') {
                return redirect()->route('customer.dashboard');
            } else {
                // Không cho phép shipper đăng nhập qua hệ thống customer
                Auth::guard('web')->logout();
                return back()->with('error', 'Vui lòng sử dụng hệ thống đăng nhập dành cho giao hàng.');
            }
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

        return redirect()->route('customer.login')->with('success', 'Đăng ký thành công');
    }

    // ===== LOGOUT =====
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->forget('login_web');

        return redirect('/');
    }
}
