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
                $name    = htmlspecialchars($validated['name']);
                $email   = htmlspecialchars($validated['email']);
                $phone   = htmlspecialchars($validated['phone'] ?? 'Không cung cấp');
                $content = nl2br(htmlspecialchars($validated['message']));
                $time    = now()->format('H:i - d/m/Y');

                $html = <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yêu cầu liên hệ mới</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 0;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

        {{-- Header --}}
        <tr>
          <td style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);border-radius:20px 20px 0 0;padding:40px 40px 30px;text-align:center;">
            <div style="display:inline-block;background:linear-gradient(135deg,#f97316,#fb923c);border-radius:14px;padding:12px 16px;margin-bottom:18px;font-size:28px;">📬</div>
            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:800;letter-spacing:-0.5px;">Yêu cầu liên hệ mới</h1>
            <p style="margin:8px 0 0;color:#94a3b8;font-size:13px;">Nhận lúc: $time</p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="background:#ffffff;padding:36px 40px;">

            <p style="margin:0 0 24px;color:#475569;font-size:14px;line-height:1.6;">
              Hệ thống <strong style="color:#f97316;">Smart Store</strong> vừa nhận được một yêu cầu liên hệ mới. Vui lòng phản hồi trong thời gian sớm nhất.
            </p>

            {{-- Info Cards --}}
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding-bottom:12px;">
                  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <tr>
                      <td style="padding:8px 16px;background:#f97316;width:4px;border-radius:12px 0 0 12px;"></td>
                      <td style="padding:14px 16px;">
                        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Họ và tên</div>
                        <div style="font-size:16px;color:#0f172a;font-weight:700;">$name</div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding-bottom:12px;">
                  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <tr>
                      <td style="padding:8px 16px;background:#3b82f6;width:4px;border-radius:12px 0 0 12px;"></td>
                      <td style="padding:14px 16px;">
                        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Địa chỉ Email</div>
                        <div style="font-size:15px;color:#3b82f6;font-weight:700;"><a href="mailto:$email" style="color:#3b82f6;text-decoration:none;">$email</a></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding-bottom:20px;">
                  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <tr>
                      <td style="padding:8px 16px;background:#10b981;width:4px;border-radius:12px 0 0 12px;"></td>
                      <td style="padding:14px 16px;">
                        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Số điện thoại</div>
                        <div style="font-size:15px;color:#0f172a;font-weight:700;">$phone</div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            {{-- Message --}}
            <div style="background:#fafafa;border:1px solid #e2e8f0;border-radius:12px;padding:20px 24px;">
              <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:10px;">Nội dung tin nhắn</div>
              <div style="font-size:14px;color:#334155;line-height:1.75;">$content</div>
            </div>

            {{-- Reply Button --}}
            <div style="text-align:center;margin-top:28px;">
              <a href="mailto:$email" style="display:inline-block;background:linear-gradient(135deg,#f97316,#fb923c);color:#ffffff;font-weight:800;font-size:14px;padding:14px 32px;border-radius:12px;text-decoration:none;letter-spacing:0.3px;">
                ↩ Trả lời ngay
              </a>
            </div>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#f8fafc;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 20px 20px;padding:20px 40px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#94a3b8;">Email tự động từ hệ thống <strong style="color:#f97316;">Smart Store</strong> · Vui lòng không trả lời trực tiếp email này.</p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;

                $message->to('chammut0009@gmail.com')
                        ->subject("📬 [{$validated['name']}] Yêu cầu liên hệ mới từ Smart Store")
                        ->html($html);
            });

            return back()->with('success', 'Cảm ơn bạn! Lời nhắn của bạn đã được gửi đi thành công.');
        } catch (\Exception $e) {
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
