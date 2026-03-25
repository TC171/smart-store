<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

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
}
