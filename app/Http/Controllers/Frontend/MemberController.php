<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\frontend\MemberLoginRequest;
use App\Http\Requests\frontend\MemberRegisterRequest;
// use App\Http\Requests\admin\UpdateProfileRequest;
use App\User;
use App\Country;
use App\Cart;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function ad()
    {
        $total_item = session()->get('total_item');
        $carts_session = session()->get('carts_session');


        $total_price = 0;
        echo "<pre>";
        var_dump($carts_session);
        foreach($carts_session as $key=>$value){
            $cart = new Cart; 
            $cart->image = $value['product_image'];
            $cart->name = $value['product_name'];
            $cart->price = $value['product_price'];
            $cart->qty = $value['product_qty'];
            $cart->id_product = $value['product_id'];
            $cart->save();
            // echo $value['product_image']."<br>";
        }       
    }
    public function index()
    {
        if (Auth::check()) {
            $id = Auth::user()->id;
            $name = Auth::user()->name;
            $email = Auth::user()->email;
            $password = Auth::user()->password;
            $phone = Auth::user()->phone;
            $country_id = Auth::user()->country;
            // $country = Country::find($country_id);
            $countries = Country::select('id', 'name')->get()->toArray();
            $country = "";
            foreach ($countries as $key => $value) {
                if ($countries[$key]['id'] == $country_id) {
                    $country = $countries[$key]['name'];
                    unset($countries[$key]);
                }
            }
            // echo $id."<br>".$name."<br>".$email;
            return view('frontend/index', compact('id', 'name', 'email', 'password', 'phone', 'countries', 'country'));
        } else {
            return view('frontend/index');
        }
    }

    public function register()
    {
        $countries = Country::all()->toArray();
        // dd($country);

        return view('frontend/member/register', compact('countries'));
    }

    public function post_register(MemberRegisterRequest $request)
    {

        $users = User::all()->toArray();
        echo $request->email;
        $check_mail = true;
        foreach ($users as $key => $value) {
            if ($value['email'] == $request->email) {
                $check_mail = false;
            }
        }
        if ($check_mail == 1) {
            $user = new User;
            $user->name = $request->name;


            $user->email = $request->email;
            $pass = bcrypt($request->password);
            $user->password = $pass;
            $user->level = 0;
            $country_id = 0;
            if (!empty($request->country)) {
                $country = Country::all()->toArray();
                foreach ($country as $key => $value) {
                    // echo $value['name'];
                    if ($request->country == $value['name']) {
                        $country_id = $value['id'];
                    }
                }
                $user->country = $country_id;
            }
            if (!empty($request->avatar)) {
                $user->avatar = $request->avatar->getClientOriginalName();
            }
            if ($user->save()) {
                if (!empty($request->avatar)) {
                    $request->avatar->move('user_img', $request->avatar->getClientOriginalName());
                }
                return redirect('frontend/login');
            } else {
                return redirect()->back()->withErrors('register failed');
            }
        } else {
            return redirect()->back()->withErrors('Email da ton tai');
        }
    }

    public function login()
    {
        return view('frontend/member/login');
    }
    public function post_login(MemberLoginRequest $request)
    {
        $login = [
            'email' => $request->email,
            'password' => $request->password,
            'level' => 0
        ];
        $remember = false;
        if ($request->remember_me) {
            $remember = true;
        }
        $check = false;
        if (Auth::attempt($login, $remember)) {
            $check = true;
            // return view('frontend/index',compact('check'));
            // return redirect('frontend/index')->with(compact('check'));
            return redirect('frontend/index')->with('success', 'check');
        } else {
            return redirect()->back()->withErrors('email or password is not correct');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('frontend/login');
    }
}
