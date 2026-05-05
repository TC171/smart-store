<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    protected function shipperId(): int
    {
        return auth('shipper')->id();
    }

    public function index()
    {
        $returns = RefundRequest::with(['order', 'user'])
            ->where('return_shipper_id', $this->shipperId())
            ->whereIn('status', [
                'approved_return',   // Chờ shipper đi lấy
                'shipper_returning', // Shipper đang giữ hàng mang về
                'goods_received',    // Đã trả hàng cho shop
                'refunded',          // Đã hoàn tiền xong
            ])
            ->latest()
            ->paginate(15);

        return view('shipper.returns.index', compact('returns'));
    }

    public function show(RefundRequest $return)
    {
        abort_unless($return->return_shipper_id === $this->shipperId(), 403);
        $return->load(['order.items.variant', 'user']);
        return view('shipper.returns.show', compact('return'));
    }

    /**
     * GỘP BƯỚC: Xác nhận lấy hàng từ khách -> Chuyển thẳng sang "Đang về shop"
     */
    public function confirmPickup(RefundRequest $return)
    {
        abort_unless($return->return_shipper_id === $this->shipperId(), 403);
        // Chỉ cho phép nếu Admin đã duyệt đơn hoàn (approved_return)
        abort_unless($return->status === 'approved_return', 422);

        $return->update([
            'status'       => 'shipper_returning', // Bỏ qua bước shipper_picking rườm rà
            'picked_up_at' => now(),
        ]);

        return back()->with('success', '🚚 Đã xác nhận lấy hàng từ khách. Đơn hàng hiện trong trạng thái "Đang chuyển về shop".');
    }

    /**
     * GỘP BƯỚC: Xác nhận giao hàng cho kho -> Chuyển sang "Chờ Admin hoàn tiền"
     */
    public function confirmDelivered(RefundRequest $return)
    {
        abort_unless($return->return_shipper_id === $this->shipperId(), 403);
        // Phải đang giữ hàng (shipper_returning) mới được bấm bàn giao
        abort_unless($return->status === 'shipper_returning', 422);

        $return->update([
            'status'      => 'goods_received',
            'returned_at' => now(),
        ]);

        $return->order->update([
            'status' => 'refunded' // Cập nhật trạng thái đơn hàng thành Đã hoàn hàng, nhưng chưa hoàn tiền
        ]);

        return back()->with('success', '📦 Đã bàn giao hàng về shop thành công! Chờ Admin kiểm hàng và hoàn tiền.');
    }
}