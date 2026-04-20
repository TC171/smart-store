<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Determine which guard is being used
            $guard = $this->getGuard($request);

            if ($guard === 'delivery') {
                return route('delivery.login');
            } elseif ($guard === 'admin') {
                return route('admin.login');
            } else {
                // Default customer guard
                return route('customer.login');
            }
        }

        return null;
    }

    /**
     * Determine which guard the request should use.
     */
    protected function getGuard(Request $request): string
    {
        // Check the current route's middleware
        if ($request->route()) {
            $middleware = $request->route()->middleware();

            if (in_array('auth:delivery', $middleware)) {
                return 'delivery';
            }
            if (in_array('auth:admin', $middleware)) {
                return 'admin';
            }
            if (in_array('auth:web', $middleware) || in_array('customer', $middleware)) {
                return 'web';
            }
        }

        // Default to web guard
        return 'web';
    }
}
