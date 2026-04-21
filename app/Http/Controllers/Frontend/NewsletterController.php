<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email'
        ], [
            'email.required' => 'Vui lòng nhập Email của bạn.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã đăng ký nhận tin từ trước đó.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        Subscriber::create([
            'email' => $request->email
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công! Bạn sẽ nhận được các ưu đãi sớm nhất từ chúng tôi.'
        ]);
    }
}
