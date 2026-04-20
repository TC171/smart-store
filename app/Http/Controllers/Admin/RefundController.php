<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Notifications\RefundStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = RefundRequest::with(['order', 'user'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $refunds = $query->paginate(15);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(RefundRequest $refund)
    {
        $refund->load(['order.items.variant', 'user']);
        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(RefundRequest $refund, Request $request)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        if ($refund->type === 'refund') {
            $refund->update([
                'status'      => 'refunded',
                'admin_note'  => $request->admin_note,
                'reviewed_at' => now(),
                'reviewed_by' => auth('admin')->id(),
            ]);
            $refund->order->update([
                'status' => 'refunded',
            ]);

            // 🔔 Gửi thông báo cho khách
            $refund->user?->notify(new RefundStatusChanged($refund));

            return back()->with('success', 'Đã duyệt và hoàn hàng ngay cho đơn hàng.');
        } else {
            $returnCode = 'RTN-' . strtoupper(uniqid());
            $refund->update([
                'status'      => 'approved_return',
                'return_code' => $returnCode,
                'admin_note'  => $request->admin_note,
                'reviewed_at' => now(),
                'reviewed_by' => auth('admin')->id(),
            ]);

            // 🔔 Gửi thông báo cho khách
            $refund->user?->notify(new RefundStatusChanged($refund));

            return back()->with('success', 'Đã duyệt yêu cầu trả hàng. Đang chờ khách hàng gửi hàng bằng mã ' . $returnCode . '.');
        }
    }

    public function confirmReceived(RefundRequest $refund)
    {
        if ($refund->status !== 'approved_return') {
            return back()->with('error', 'Trạng thái yêu cầu không hợp lệ để xác nhận nhận hàng.');
        }

        $refund->update(['status' => 'refunded']);
        $refund->order->update([
            'status' => 'refunded',
        ]);

        // 🔔 Gửi thông báo đã hoàn tiền
        $refund->user?->notify(new RefundStatusChanged($refund));

        return back()->with('success', 'Đã xác nhận nhận được hàng trả về. Đơn hàng đã được ghi nhận hoàn hàng.');
    }

    public function reject(RefundRequest $refund, Request $request)
    {
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

        // 🔔 Gửi thông báo từ chối cho khách
        $refund->user?->notify(new RefundStatusChanged($refund));

        return back()->with('success', 'Đã từ chối yêu cầu hoàn hàng.');
    }
}
