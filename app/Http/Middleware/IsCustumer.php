<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCustumer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa login
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // Nếu KHÔNG phải customer
        if (auth()->user()->role !== 'customer') {
            abort(403, 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}
