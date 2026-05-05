<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    /**
     * Hiển thị form yêu cầu hoàn hàng cho đơn hàng.
     */
    public function create(Order $order)
    {
        // Kiểm tra quyền sở hữu thủ công
        if ($order->user_id !== auth('web')->id()) {
            abort(403);
        }

        // Chỉ cho phép với đơn đã hoàn thành
        if ($order->status !== 'completed') {
            return redirect()->route('customer.orders')
                ->with('error', 'Chỉ có thể yêu cầu hoàn hàng/hoàn tiền cho đơn đã hoàn thành.');
        }

        // Kiểm tra đã có yêu cầu đang chờ duyệt chưa
        $existingRequest = RefundRequest::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'approved_return'])
            ->first();

        $order->load(['items.variant.product', 'refundRequests']);

        return view('frontend.customer.refund-request', compact('order', 'existingRequest'));
    }

    /**
     * Lưu yêu cầu hoàn hàng với video bằng chứng.
     */
    public function store(Request $request, Order $order)
    {
        // Kiểm tra quyền sở hữu thủ công
        if ($order->user_id !== auth('web')->id()) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return redirect()->route('customer.orders')
                ->with('error', 'Không thể gửi yêu cầu hoàn hàng cho đơn này.');
        }

        // Kiểm tra đã có yêu cầu chưa xử lý
        $existing = RefundRequest::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'approved_return'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Bạn đã có yêu cầu đang xử lý cho đơn hàng này.');
        }

        $request->validate([
            'type'   => 'required|in:refund,return',
            'reason' => 'required|string|min:10|max:1000',
            'video'  => 'required|file|mimetypes:video/mp4,video/avi,video/quicktime,video/x-msvideo,video/webm|max:20480',
        ], [
            'video.required'   => 'Video bóc hàng là bắt buộc.',
            'video.mimetypes'  => 'Video phải có định dạng MP4, AVI, MOV hoặc WebM.',
            'video.max'        => 'Video không được vượt quá 20MB.',
            'reason.required'  => 'Vui lòng nhập lý do hoàn hàng.',
            'reason.min'       => 'Lý do hoàn hàng phải có ít nhất 10 ký tự.',
        ]);

        $videoPath    = null;
        $originalName = null;

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $file         = $request->file('video');
            $originalName = $file->getClientOriginalName();
            $videoPath    = $file->store('refund-videos', 'public');
        }

        RefundRequest::create([
            'order_id'            => $order->id,
            'user_id'             => auth('web')->id(),
            'type'                => $request->type,
            'reason'              => $request->reason,
            'video_path'          => $videoPath,
            'video_original_name' => $originalName,
            'status'              => 'pending',
            'return_code'         => null,
        ]);

        return redirect()->route('customer.orders')
            ->with('success', 'Yêu cầu hoàn hàng đã được gửi! Chúng tôi sẽ xem xét và phản hồi trong 1-3 ngày làm việc.');
    }
}

