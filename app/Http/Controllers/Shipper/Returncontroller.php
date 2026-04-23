<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;

class ReturnController extends Controller
{
    protected function shipperId(): int
    {
        return auth('shipper')->id();
    }

    /**
     * Danh sách đơn hoàn hàng mà admin đã chỉ định cho shipper này.
     */
    public function index()
    {
        $returns = RefundRequest::with(['order', 'user'])
            ->where('return_shipper_id', $this->shipperId())
            ->whereIn('status', [
                'approved_return',
                'shipper_picking',
                'shipper_returning',
                'goods_received',
                'refunded',
            ])
            ->latest()
            ->paginate(15);

        return view('shipper.returns.index', compact('returns'));
    }

    /**
     * Chi tiết đơn hoàn hàng.
     */
    public function show(RefundRequest $return)
    {
        abort_unless($return->return_shipper_id === $this->shipperId(), 403);
        $return->load(['order.items.variant', 'user']);
        return view('shipper.returns.show', compact('return'));
    }

    /**
     * Shipper xác nhận đã đến lấy hàng từ tay khách.
     */
    public function confirmPickup(RefundRequest $return)
    {
        abort_unless($return->return_shipper_id === $this->shipperId(), 403);
        abort_unless($return->status === 'approved_return', 422);

        $return->update([
            'status'       => 'shipper_picking',
            'picked_up_at' => now(),
        ]);

        return back()->with('success', '🚚 Đã xác nhận lấy hàng từ khách. Vui lòng chuyển hàng về shop.');
    }

    /**
     * Shipper xác nhận đang trên đường về shop.
     */
    public function confirmReturning(RefundRequest $return)
    {
        abort_unless($return->return_shipper_id === $this->shipperId(), 403);
        abort_unless($return->status === 'shipper_picking', 422);

        $return->update(['status' => 'shipper_returning']);

        return back()->with('success', '🔄 Đã cập nhật: đang chuyển hàng về shop.');
    }

    /**
     * Shipper xác nhận đã giao hàng về đến shop.
     * Sau bước này Admin sẽ xác nhận và hoàn tiền cho khách.
     */
    public function confirmDelivered(RefundRequest $return)
    {
        abort_unless($return->return_shipper_id === $this->shipperId(), 403);
        abort_unless($return->status === 'shipper_returning', 422);

        $return->update([
            'status'      => 'goods_received',
            'returned_at' => now(),
        ]);

        return back()->with('success', '📦 Hàng đã về shop! Admin sẽ kiểm tra và xác nhận hoàn tiền cho khách.');
    }
}