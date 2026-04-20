<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShipperMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('ShipperMiddleware called', [
            'path' => $request->path(),
            'method' => $request->method(),
            'auth_check' => auth('delivery')->check(),
            'auth_id' => auth('delivery')->id(),
            'user_role' => auth('delivery')->user()?->role,
        ]);

        if (! auth('delivery')->check()) {
            \Log::warning('Delivery not authenticated, redirecting to login');
            return redirect('/delivery/login');
        }

        if (auth('delivery')->user()->role !== 'shipper') {
            \Log::error('User is not shipper', [
                'user_id' => auth('delivery')->id(),
                'role' => auth('delivery')->user()->role,
            ]);
            return redirect()->route('home')->with('error', 'Chỉ tài khoản giao hàng mới có thể truy cập trang này.');
        }

        \Log::info('ShipperMiddleware passed');
        return $next($request);
    }
}
