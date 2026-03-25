<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('customer.*', function ($view) {
            $userId = auth('web')->id();
            if (! $userId) {
                return;
            }

            $stats = Order::query()
                ->where('user_id', $userId)
                ->selectRaw('COUNT(*) as total_orders')
                ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders")
                ->selectRaw('COALESCE(SUM(total_amount), 0) as total_spent')
                ->first();

            $view->with([
                'customerTotalOrders' => (int) ($stats->total_orders ?? 0),
                'customerCompletedOrders' => (int) ($stats->completed_orders ?? 0),
                'customerTotalSpent' => (float) ($stats->total_spent ?? 0),
            ]);
        });
    }
}
