<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ chỉ tính doanh thu hợp lệ (completed + paid)
        $totalRevenue = \App\Models\Order::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        // ✅ số đơn hợp lệ
        $totalOrders = \App\Models\Order::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->count();

        $totalProducts = \App\Models\Product::where('status', 1)->count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        // ===== KPI TODAY (THÊM NGAY ĐÂY) =====
$todayRevenue = \App\Models\Order::where('status', 'completed')
    ->where('payment_status', 'paid')
    ->whereDate('created_at', now())
    ->sum('grand_total');

$todayOrders = \App\Models\Order::where('status', 'completed')
    ->where('payment_status', 'paid')
    ->whereDate('created_at', now())
    ->count();

$todayCustomers = \App\Models\User::where('role', 'customer')
    ->whereDate('created_at', now())
    ->count();

// nếu bạn có bảng order_items thì dùng
$todayProducts = \App\Models\OrderItem::whereDate('created_at', now())
    ->sum('quantity');

        // đơn gần đây (giữ nguyên)
        $recentOrders = \App\Models\Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // ✅ doanh thu theo tháng (fix timezone)
        $monthlyRevenue = \App\Models\Order::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->selectRaw("
                MONTH(CONVERT_TZ(created_at, '+00:00', '+07:00')) as month,
                SUM(grand_total) as revenue
            ")
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        return view('admin.dashboard', compact(
    'totalRevenue',
    'totalOrders',
    'totalProducts',
    'totalCustomers',
    'recentOrders',
    'monthlyRevenue',

    // ✅ thêm dòng này
    'todayRevenue',
    'todayOrders',
    'todayCustomers',
    'todayProducts'
));
    }

    // 🔥 API CHO CHART
    public function revenueChart(Request $request)
{
    $type = $request->type ?? 'month';

    $from = $request->from
        ? Carbon::parse($request->from)->startOfDay()
        : now()->startOfYear();

    $to = $request->to
        ? Carbon::parse($request->to)->endOfDay()
        : now()->endOfYear();

    $query = \App\Models\Order::where('status', 'completed')
        ->where('payment_status', 'paid')
        ->whereBetween('created_at', [$from, $to]);

    $tz = '+07:00';

    // 🔥 RAW DATA
    $raw = [];

    switch ($type) {

        case 'day':
            $raw = $query
                ->selectRaw("DATE(CONVERT_TZ(created_at, '+00:00', '$tz')) as label, SUM(grand_total) as total")
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            // ===== FILL MISSING DAYS =====
            $result = [];
            $period = CarbonPeriod::create($from->copy()->startOfDay(), $to->copy()->startOfDay());

            foreach ($period as $date) {
                $key = $date->format('Y-m-d');

                $result[] = [
                    'label' => $key,
                    'total' => $raw[$key] ?? 0
                ];
            }

            return response()->json($result);

        case 'week':
            $raw = $query
                ->selectRaw("YEARWEEK(CONVERT_TZ(created_at, '+00:00', '$tz'), 1) as label, SUM(grand_total) as total")
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $result = [];

            $start = $from->copy()->startOfWeek();
            $end = $to->copy()->endOfWeek();

            while ($start <= $end) {

                $key = $start->format('oW');

                $result[] = [
                    'label' => $key,
                    'total' => $raw[$key] ?? 0
                ];

                $start->addWeek();
            }

            return response()->json($result);

        case 'month':
            $raw = $query
                ->selectRaw("DATE_FORMAT(CONVERT_TZ(created_at, '+00:00', '$tz'), '%Y-%m') as label, SUM(grand_total) as total")
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $result = [];

            $start = $from->copy()->startOfMonth();
            $end = $to->copy()->startOfMonth();

            while ($start <= $end) {

                $key = $start->format('Y-m');

                $result[] = [
                    'label' => $key,
                    'total' => $raw[$key] ?? 0
                ];

                $start->addMonth();
            }

            return response()->json($result);

        case 'quarter':
            $raw = $query
                ->selectRaw("CONCAT(YEAR(CONVERT_TZ(created_at, '+00:00', '$tz')), '-Q', QUARTER(CONVERT_TZ(created_at, '+00:00', '$tz'))) as label, SUM(grand_total) as total")
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $result = [];

            $startYear = $from->year;
            $endYear = $to->year;

            for ($y = $startYear; $y <= $endYear; $y++) {
                for ($q = 1; $q <= 4; $q++) {

                    $key = $y . '-Q' . $q;

                    $result[] = [
                        'label' => $key,
                        'total' => $raw[$key] ?? 0
                    ];
                }
            }

            return response()->json($result);

        case 'year':
            $raw = $query
                ->selectRaw("YEAR(CONVERT_TZ(created_at, '+00:00', '$tz')) as label, SUM(grand_total) as total")
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $result = [];

            for ($y = $from->year; $y <= $to->year; $y++) {
                $result[] = [
                    'label' => (string)$y,
                    'total' => $raw[$y] ?? 0
                ];
            }

            return response()->json($result);
    }

    return response()->json([]);
}
}