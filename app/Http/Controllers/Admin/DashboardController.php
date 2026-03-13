<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = \App\Models\Order::sum('grand_total');
        $totalOrders = \App\Models\Order::count();
        $totalProducts = \App\Models\Product::where('status', 1)->count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        
        $recentOrders = \App\Models\Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $monthlyRevenue = \App\Models\Order::selectRaw('MONTH(created_at) as month, SUM(grand_total) as revenue')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'totalOrders', 
            'totalProducts', 
            'totalCustomers',
            'recentOrders',
            'monthlyRevenue'
        ));
    }
}
