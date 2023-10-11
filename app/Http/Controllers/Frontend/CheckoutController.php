<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use App\User;
use App\Product;
use Mail;
use App\History;
use App\Cart;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\frontend\MemberRegisterRequest;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $countries = Country::all()->toArray();
        return view('frontend/checkout/checkout', compact('countries'));
    }
    public function post_checkout(Request $request)
    {
        if (!Auth::check()) {

            $check_login = true;
            $users = User::all()->toArray();
            foreach ($users as $key => $value) {
                if ($value['email'] == $request->email) {
                    $check_login = false;
                }
            }

            if ($check_login == false) {
                return  redirect()->back()->withErrors('Email da ton tai');
            }
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $pass = bcrypt($request->password);
            $user->password = $pass;
            $user->phone = $request->phone;
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
            $check = "";
            if ($user->save()) {
                if (!empty($request->avatar)) {
                    $request->avatar->move('user_img', $request->avatar->getClientOriginalName());
                }

                //Login
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
                    $getRequest = $request->all();
                    $idProduct = session()->get('carts_session');
                    $item = 0;
                    $sum = 0;
                    foreach ($idProduct as $key => $value) {
                        $product[] = Product::find($idProduct[$key]['product_id'])->toArray();
                        $item += $idProduct[$key]['product_qty'];
                        $product[$key]['qty'] = $idProduct[$key]['product_qty'];
                        $sum = $sum + $idProduct[$key]['product_price'] * $idProduct[$key]['product_qty'];
                    }


                    foreach ($product as $key => $value) {
                    }
                    $phone = $request->phone;
                    $emailTo = $getRequest["email"];
                    $subject = "Mail order product";
                    $name = $request->name;
                    $email = $request->email;
                    // $phone =
                    Mail::send(
                        'frontend.email.email_template',
                        array(
                            'name' => $name,
                            'item' => $item,
                            'product' => $product,
                            'sum' => $sum,
                            'email' => $email,
                            'phone' => $phone
                        ),
                        function ($message) use ($subject, $emailTo, $name) {
                            $message->from($emailTo, 'Mail order product : ' . $name);
                            $message->to('dongvutiennghia@gmail.com');
                            $message->subject($subject);
                        }
                    );

                    //HISTORY
                    $history = new History;
                    $history->email = $request->email;
                    $history->price = $sum;
                    $history->name = $request->name;
                    $history->id_user = Auth::user()->id;
                    $history->phone = Auth::user()->phone;
                    $history->qty = $item;
                    if ($history->save()) {
                        $carts_session = session()->get('carts_session');
                        foreach ($carts_session as $key => $value) {
                            $cart = new Cart;
                            $cart->image = $value['product_image'];
                            $cart->name = $value['product_name'];
                            $cart->price = $value['product_price'];
                            $cart->qty = $value['product_qty'];
                            $cart->id_product = $value['product_id'];

                            $cart->id_user = Auth::id();

                            $cart->save();
                            // echo $value['product_image']."<br>";
                        }
                        session()->forget('carts_session');
                        session()->forget('total_item');
                        return redirect()->back()->with('success', 'success');
                    }
                } else {
                    return redirect()->back()->withErrors('email or password is not correct');
                }
            } else {

                session()->put('check_login', $check);
                // return redirect()->back()->withErrors('register failed');
            }
        } else {
            $getRequest = $request->all();
            $idProduct = session()->get('carts_session');
            $item = 0;
            $sum = 0;
            $qty = 0;

            foreach ($idProduct as $key => $value) {
                $product[] = Product::find($idProduct[$key]['product_id'])->toArray();
                $item += $idProduct[$key]['product_qty'];
                $product[$key]['qty'] = $idProduct[$key]['product_qty'];
                $sum = $sum + $idProduct[$key]['product_price'] * $idProduct[$key]['product_qty'];
                $qty += $idProduct[$key]['product_qty'];
            }
            // echo "<pre>";
            // var_dump($product);
            foreach ($product as $key => $value) {
                // $sum = $sum+$value['price']*$value['qty'];
            }
            $emailTo = $getRequest["email"];
            $subject = "Mail order product";
            $name = $request->name;
            $email = $request->email;

            Mail::send(
                'frontend.email.email_template',
                array(
                    'name' => $name,
                    'item' => $item,
                    'product' => $product,
                    'sum' => $sum,
                    'email' => $email,
                    'phone' => Auth::user()->phone
                ),
                function ($message) use ($subject, $emailTo, $name) {
                    $message->from($emailTo, 'Mail order product : ' . $name);
                    $message->to('dongvutiennghia@gmail.com');
                    $message->subject($subject);
                }
            );
            //HISTORY
            $history = new History;
            $history->email = $request->email;
            $history->price = $sum;
            $history->name = $request->name;
            $history->id_user = Auth::user()->id;
            $history->phone = Auth::user()->phone;
            $history->qty = $qty;
            if ($history->save()) {
                $carts_session = session()->get('carts_session');
                foreach ($carts_session as $key => $value) {
                    $cart = new Cart;
                    $cart->image = $value['product_image'];
                    $cart->name = $value['product_name'];
                    $cart->price = $value['product_price'];
                    $cart->qty = $value['product_qty'];
                    $cart->id_product = $value['product_id'];
                    $cart->id_user = Auth::id();
                    $cart->save();

                }
                session()->forget('carts_session');
                session()->forget('total_item');
                return redirect()->back()->with('success', 'Order thanh cong');
            }
        }
    }
}
