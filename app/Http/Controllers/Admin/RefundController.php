<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Notifications\RefundStatusChanged;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = RefundRequest::with(['order', 'user', 'returnShipper'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $refunds = $query->paginate(15);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(RefundRequest $refund)
    {
        $refund->load(['order.items.variant', 'order.shipper', 'user', 'returnShipper']);
        return view('admin.refunds.show', compact('refund'));
    }

    /**
     * Admin duyệt yêu cầu hoàn hàng.
     * - type=refund: hoàn tiền ngay.
     * - type=return: tự động giao cho shipper đã giao đơn hàng gốc.
     */
    public function approve(RefundRequest $refund, Request $request)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $request->validate(['admin_note' => 'nullable|string|max:500']);

        if ($refund->type === 'refund') {
            // Hoàn tiền trực tiếp
            $refund->update([
                'status'      => 'refunded',
                'admin_note'  => $request->admin_note,
                'reviewed_at' => now(),
                'reviewed_by' => auth('admin')->id(),
            ]);
            $refund->order->update([
                'status'         => 'refunded',
                'payment_status' => 'refunded',
            ]);

            $refund->user?->notify(new RefundStatusChanged($refund));

            return back()->with('success', 'Đã duyệt và ghi nhận hoàn tiền cho đơn hàng.');
        } else {
            // Hoàn hàng: tự động dùng shipper đã giao đơn hàng gốc
            $shipperId = $refund->order->shipper_id;

            if (!$shipperId) {
                return back()->with('error', 'Đơn hàng này chưa có shipper phụ trách. Không thể tạo yêu cầu hoàn hàng qua shipper.');
            }

            $returnCode  = 'RTN-' . strtoupper(uniqid());
            $shipperName = $refund->order->shipper->name ?? 'Shipper';

            $refund->update([
                'status'            => 'approved_return',
                'return_code'       => $returnCode,
                'admin_note'        => $request->admin_note,
                'reviewed_at'       => now(),
                'reviewed_by'       => auth('admin')->id(),
                'return_shipper_id' => $shipperId,
            ]);

            $refund->user?->notify(new RefundStatusChanged($refund));

            return back()->with('success', "Đã duyệt. Đơn hoàn hàng đã được giao cho shipper {$shipperName}. Mã hoàn: {$returnCode}.");
        }
    }

    /**
     * Admin xác nhận hàng đã về shop → chuyển sang đã hoàn tiền.
     */
    public function confirmReceived(RefundRequest $refund)
    {
        if ($refund->status !== 'goods_received') {
            return back()->with('error', 'Chỉ có thể xác nhận hoàn tiền khi hàng đã về shop.');
        }

        $refund->update([
            'status'      => 'refunded',
            'returned_at' => now(),
        ]);
        $refund->order->update([
            'status'         => 'refunded',
            'payment_status' => 'refunded',
        ]);

        $refund->user?->notify(new RefundStatusChanged($refund));

        return back()->with('success', 'Đã xác nhận nhận hàng về shop và hoàn tiền cho khách hàng.');
    }

    public function reject(RefundRequest $refund, Request $request)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $request->validate([
            'admin_note' => 'required|string|max:500',
        ], [
            'admin_note.required' => 'Vui lòng nhập lý do từ chối.'
        ]);

        $refund->update([
            'status'      => 'rejected',
            'admin_note'  => $request->admin_note,
            'reviewed_at' => now(),
            'reviewed_by' => auth('admin')->id(),
        ]);

        $refund->user?->notify(new RefundStatusChanged($refund));

        return back()->with('success', 'Đã từ chối yêu cầu hoàn hàng.');
    }
}