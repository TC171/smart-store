<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->search) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $coupons = $query->latest()->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'required|date|after_or_equal:' . ($request->starts_at ?? 'today'),
            'status' => 'required|boolean',
        ]);

        Coupon::create($request->only([
            'code', 'type', 'value', 'min_order_amount', 'max_discount', 
            'usage_limit', 'starts_at', 'expires_at', 'status'
        ]));

        return redirect()->route('coupons.index')
            ->with('success', 'Thêm mã giảm giá thành công');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'required|date|after_or_equal:' . ($request->starts_at ?? 'today'),
            'status' => 'required|boolean',
        ]);

        $coupon->update($request->only([
            'code', 'type', 'value', 'min_order_amount', 'max_discount', 
            'usage_limit', 'starts_at', 'expires_at', 'status'
        ]));

        return redirect()->route('coupons.index')
            ->with('success', 'Cập nhật mã giảm giá thành công');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Xóa mã giảm giá thành công');
    }
}
