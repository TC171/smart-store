<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\Order;

class ReturnController extends Controller
{
    protected function shipperId(): int
    {
        return auth('shipper')->id();
    }

    /**
     * Danh sách đơn hoàn hàng liên quan đến shipper này.
     * Shipper chỉ xem được, không thể sửa gì.
     */
    public function index()
    {
        // Lấy các đơn hoàn hàng mà shipper này đã từng giao
        $returns = RefundRequest::with(['order', 'user'])
            ->whereHas('order', function ($q) {
                $q->where('shipper_id', $this->shipperId());
            })
            ->latest()
            ->paginate(15);

        return view('shipper.returns.index', compact('returns'));
    }

    /**
     * Chi tiết một đơn hoàn hàng — chỉ đọc.
     * Shipper không có quyền thay đổi bất kỳ trạng thái nào.
     */
    public function show(RefundRequest $return)
    {
        // Đảm bảo đơn này thuộc shipper hiện tại
        $order = Order::where('id', $return->order_id)
            ->where('shipper_id', $this->shipperId())
            ->firstOrFail();

        $return->load(['order.items.variant', 'user']);

        return view('shipper.returns.show', compact('return'));
    }
}