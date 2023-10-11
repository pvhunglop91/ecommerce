<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use App\Category;
use App\Brand;
use App\Product;


class SearchController extends Controller
{
    public function search()
    {
        $categories = Category::all()->toArray();
        $brands = Brand::all()->toArray();

        return view('frontend/search/search', compact('categories', 'brands'));
    }
    public function post_search(Request $request)
    {
        $products = Product::query();
        if ($request->has('name')) {
            $products->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('price')) {
            $price =  $request->price;
            $arr_price = explode('-', $price);
            $count = count($arr_price);
            
            if ($count == 1) {
                $price = 0;
                if ($arr_price['0'] == 300) {
                    $price =  (int)$arr_price['0'];
                    $products->where('price', '<', $price);
                } else if($arr_price['0'] == 700) {
                    $price =  700;
                    $products->where('price', '>', $price);
                }
            } else if ($count == 2) {
                $min = $arr_price['0'];
                $max = $arr_price['1'];
                $products->where('price', '>=', $min)
                    ->where('price', '<', $max);
            }
        }


        if ($request->has('category')) {
            $check = false;
            $categories = Category::all()->toArray();
            foreach($categories as $key=>$value){
                if($value['id']==$request->category){
                    $check=true;
                }
            }
            
            if($check==1){
                $products->where('id_category', $request->category);
            }
        }

        if ($request->has('brand')) {
            // echo $request->category."<br>";
            $check = false;
            $brands = Brand::all()->toArray();
            foreach($brands as $key=>$value){
                if($value['id']==$request->brand){
                    $check=true;
                }
            }
            
            if($check==1){
                $products->where('id_brand', $request->brand);
            }
            // $products->where('id_brand', $request->brand);
        }
        if ($request->has('status')) {
            $check = false;
            $status = Product::all()->toArray();
            
            foreach($status as $key=>$value){
                if($value['status']==$request->status){
                    $check=true;                    
                }
            }
            if($check==1){
                $products->where('status', $request->status);
            }
            // $products->where('id_brand', $request->brand);
        }
        // echo "<pre>";
        $products = $products->get()->toArray();

        // var_dump($products);


        $categories = Category::all()->toArray();
        $brands = Brand::all()->toArray();

        return view('frontend/search/search', compact('categories', 'brands','products'));
    }
    public function search_name(Request $request)
    {
        $products = Product::query();
        if ($request->has('name')) {
            $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products = $products->get()->toArray();
        return view('frontend/search/search_name',compact('products'));
    }
    public function ajax_search_price(Request $request)
    {
        $price = $request->arr_price;
        $arr_price = explode(':',$price);
        $min = $arr_price['0'];
        $max = $arr_price['1'];
        
        $products = Product::query();
        $products->where('price', '>=',$min)
                 ->where('price','<=',$max);
        $products = $products->get()->toArray();

        return response()->json(['products'=>$products]);
    }
    public function search_cate(Request $request)
    {
        $products = Product::select()->where('id_category',$request->id)->get()->toArray();
        // echo "<pre>";
        // var_dump($products);
        // return 
        return view('frontend/search/search_cate',compact('products'));
    }
    public function search_brand(Request $request)
    {
        $products = Product::select()->where('id_brand',$request->id)->get()->toArray();
        // echo "<pre>";
        // var_dump($products);
        // return 
        return view('frontend/search/search_brand',compact('products'));
    }
}
