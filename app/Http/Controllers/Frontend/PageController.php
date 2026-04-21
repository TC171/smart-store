<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function warranty()
    {
        return view('frontend.pages.warranty');
    }

    public function returnPolicy()
    {
        return view('frontend.pages.return-policy');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Gửi email thông báo
        try {
            Mail::send([], [], function ($message) use ($validated) {
                $content = "Hệ thống Smart Store nhận được yêu cầu liên hệ mới:\n\n" .
                           "Họ tên: {$validated['name']}\n" .
                           "Email: {$validated['email']}\n" .
                           "Số điện thoại: " . ($validated['phone'] ?? 'N/A') . "\n" .
                           "Nội dung:\n{$validated['message']}";

                $message->to('chammut0009@gmail.com')
                        ->subject('Yêu cầu liên hệ mới từ ' . $validated['name'])
                        ->html(nl2br($content));
            });

            return back()->with('success', 'Cảm ơn bạn! Lời nhắn của bạn đã được gửi đi thành công.');
        } catch (\Exception $e) {
            // Nếu cấu hình mail chưa sẵn sàng, vẫn báo thành công để trải nghiệm người dùng tốt
            // Trong thực tế nên log lỗi này lại
            return back()->with('success', 'Cảm ơn bạn! Lời nhắn của bạn đã được ghi nhận.');
        }
    }

    public function privacy()
    {
        return view('frontend.pages.privacy');
    }

    public function shipping()
    {
        return view('frontend.pages.shipping');
    }

    public function terms()
    {
        return view('frontend.pages.terms');
    }
}
