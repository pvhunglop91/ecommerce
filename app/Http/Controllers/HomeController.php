<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\admin\UpdateProfileRequest;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function get_pages_profile(Request $request)
    {
        if(Auth::check()){
            $id = Auth::user()->id;
            $name = Auth::user()->name;
            $email = Auth::user()->email;
            $password = Auth::user()->password;
            $phone = Auth::user()->phone;
            $country = Auth::user()->country;

            return view('admin/dashboard/pages_profile',compact('id','name','email','password','phone','country'));
        }

    }
    public function post_pages_profile(UpdateProfileRequest $request)
    {
    //    $userid = Auth::id();
    //    $user =
    echo "asd";


    }

    // public function test()
    // {
    //     echo "hello laravel 7";
    // }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin/dashboard/dashboard');
    }
}
