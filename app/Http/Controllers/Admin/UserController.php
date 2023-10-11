<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facade\Ignition\Support\Packagist\Package;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('frontend/member/index');
    }
    public function cart()
    {
        return view('frontend/member/cart');
    }
    public function error_404()
    {
        return view('frontend/member/404');
    }
    public function blog_single()
    {
        return view('frontend/member/blog-single');
    }
    public function blog()
    {
        return view('frontend/member/blog');
    }
    public function checkout()
    {
        return view('frontend/member/checkout');
    }
    public function contact_us()
    {
        return view('frontend/member/contact-us');
    }
    public function login()
    {
        return view('frontend/member/login');
    }
    public function product_details()
    {
        return view('frontend/member/product-details');
    }
    public function shop()
    {
        return view('frontend/member/shop');
    }
}
