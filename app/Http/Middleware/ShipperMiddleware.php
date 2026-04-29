<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShipperMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('shipper')->check()) {
            return redirect()->route('shipper.login');
        }

        $user = auth('shipper')->user();

        if ($user->role !== 'shipper') {
            Auth::guard('shipper')->logout();
            return redirect()->route('shipper.login')->with('error', 'Tài khoản không hợp lệ.');
        }

        if ($user->status != 1) {
            Auth::guard('shipper')->logout();
            return redirect()->route('shipper.login')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
        }

        return $next($request);
    }
}
