<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class AjaxController extends Controller
{
    public function ajaxRequest()
    {
        return view('frontend/blog/ajaxRequest');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function ajaxRequestPost(Request $request)
    // {
    //     $input = $request->all();
    //     \Log::info($input);
    //     // var_dump($input);
    //     return response()->json(['success'=>'Got Simple Ajax Request.']);
    //     // return response()->with('success',compact($input));
    // }
}
