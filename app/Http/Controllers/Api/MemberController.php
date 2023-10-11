<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\frontend\MemberLoginRequest;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    
    public function post_login(Request $request)
    {
        // echo "asd";
        // exit;
        $login = [
            'email' => $request->email,
            'password' => $request->password,
            'level' => 0
        ];
        $remember = false;
        if ($request->remember_me) {
            $remember = true;
        }
        
        if (Auth::attempt($login, $remember)) {
            // return view('frontend/index',compact('check'));
            // return redirect('frontend/index')->with(compact('check'));
            return response()->json([
                'response' => 'success',
                'errors' => ['success' => 'dang nhap thanh cong'],
            ]);
            // $this->successStatus); 
        } else {
            return response()->json([
                'response' => 'error',
                'errors' => ['errors' => 'invalid email or password'],
            ]);
            // $this->successStatus); 

        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('frontend/login');
    }
}
