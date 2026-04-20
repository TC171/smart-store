<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RefundController extends Controller
{
    public function index()
    {
        return view('admin.refunds.index');
    }

    public function show($id)
    {
        return view('admin.refunds.show');
    }

    public function approve($id)
    {
        return back();
    }

    public function confirmReceived($id)
    {
        return back();
    }

    public function reject($id)
    {
        return back();
    }
}