<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Country;
use App\Product;
use App\Cart;
class HomeController extends Controller
{
    public function index(Request $request)
    {


            $id = Auth::id();
            $products = Product::paginate(9);
            $cart = Cart::select()->get()->toArray();
            $qty_arr = Cart::select('qty')->get()->toArray();
            $total_item = 0;
            foreach($qty_arr as $key=>$value){
                // $total_item+=$value['qty'];
            }
            // $total_item=0;
            // session()->put('total_item', $total_item);
            $total_item = $request->session()->get('total_item');
            return view('frontend/index',compact('products','total_item'));


    }
}
        // session()->has('cart'): kiem tra ss cart co k?
        // session()->get('cart'): lay ss cart ra
        // session()->push('cart',$array); dua 1 mang vao SS Cart
        // session()->put('cart',...); update
