<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('shipper')->check()) {
            return redirect()->route('shipper.dashboard');
        }

        return view('shipper.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])
            ->where('role', 'shipper')
            ->first();

        if (!$user || $user->status != 1) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Tài khoản không tồn tại hoặc đã bị khóa.']);
        }

        if (Auth::guard('shipper')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('shipper.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.']);
    }

    public function logout(Request $request)
    {
        // Chỉ logout guard shipper, KHÔNG invalidate toàn bộ session
        // để tránh mất CSRF token và conflict với các guard khác
        Auth::guard('shipper')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('shipper.login');
    }
}


//      if (!$user || $user->status != 1) {
//             return back()
//                 ->withInput($request->only('email'))
//                 ->withErrors(['email' => 'Tài khoản không tồn tại hoặc đã bị khóa.']);
//         }

//         if (Auth::guard('shipper')->attempt($credentials, $request->boolean('remember'))) {
//             $request->session()->regenerate();
//             return redirect()->intended(route('shipper.dashboard'));
//         }

//         return back()
//             ->withInput($request->only('email'))
//             ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.']);
//     }

//     public function logout(Request $request)
//     {
//         // Chỉ logout guard shipper, KHÔNG invalidate toàn bộ session
//         // để tránh mất CSRF token và conflict với các guard khác
//         Auth::guard('shipper')->logout();
//         $request->session()->regenerateToken();

//         return redirect()->route('shipper.login');
//     }
// }

