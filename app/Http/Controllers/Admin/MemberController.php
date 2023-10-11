<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
class MemberController extends Controller
{
    //
    public function member()
    {
        $users = User::select()->where('level','0')->orderby('id','desc')->paginate(5);
        return view('admin/user/member',compact('users'));
       
        
    }
    public function member_delete(Request $request){
        // echo "asd";
        $user = User::findOrFail($request->id);
        if($user->delete()){
            return redirect()->back()->with('success','xoa user thanh cong');
        }else{
            echo "asd";
            return redirect()->back()->withErrors('da xay ra chut loi');
        }
    }
}
