<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
    public function cart()
    {
       
        return view('frontend/cart/cart');
    }
    public function ajax_up_product(Request $request)
    {
        if(session()->has('carts_session')){
            $check = true;
            $pro_id = $request->id_product;
            $cart = session()->get('carts_session');
            foreach($cart as $key=>$value){
                if($pro_id==$value['product_id']){
                    $cart[$key]['product_qty']+=1;
                    $check=false;
                }
            }
            if($check==false){
                session()->put('carts_session',$cart);
            }
        }
        if(session()->has('total_item'))
        { 
            $total_item = session()->get('total_item') ;
            $total_item++;
            session()->put('total_item',$total_item);
        }
        return response()->json(['success'=>'oke']);
    }

    public function ajax_down_product(Request $request)
    {
        if(session()->has('carts_session')){
            $check = true;
            $check_qty = true;
            $pro_id = $request->id_product;
            $cart = session()->get('carts_session');
            foreach($cart as $key=>$value){
                if($pro_id==$value['product_id']){
                    $cart[$key]['product_qty']-=1;
                    if($cart[$key]['product_qty']==0){
                        unset($cart[$key]);
                    }
                    $check=false; 
                }
            }
            if($check==false){
                session()->put('carts_session',$cart);
            }
        }
        if(session()->has('total_item'))
        { 
            $total_item = session()->get('total_item') ;
            $total_item--;
            session()->put('total_item',$total_item);
        }
        return response()->json(['success'=>'oke']);
    }
    public function ajax_delete_product(Request $request)
    {
        if(session()->has('carts_session')){
            $check = true;
            $pro_id = $request->id_product;
            $cart = session()->get('carts_session');
            foreach($cart as $key=>$value){
                if($pro_id==$value['product_id']){
                    
                    unset($cart[$key]);
                    $check=false; 
                }
            }
            if($check==false){
                session()->put('carts_session',$cart);
            }
        }
        if(session()->has('total_item'))
        { 
            $total_item = session()->get('total_item') ;
            $qty = (int)$request->qty_s;
            $total_item-=$qty;
            session()->put('total_item',$total_item);
        }

        return response()->json(['success'=>'oke']);
    }
}
