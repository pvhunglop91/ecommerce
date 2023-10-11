<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Country;
use App\Http\Requests\admin\UpdateProfileRequest;
use App\Category;
use App\Brand;
use App\Cart;
use App\Test;
use App\Product;
use App\History;

use Image;
use DateTimeZone;
use App\Http\Requests\frontend\AddProductRequest;

class AccountController extends Controller
{
    public function account()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $user = User::findOrFail($user_id);

            $countries = Country::select('id', 'name')->get()->toArray();
            $country = "";
            foreach ($countries as $key => $value) {
                if ($countries[$key]['id'] == $user->country) {
                    $country = $countries[$key]['name'];
                    unset($countries[$key]);
                }
            }

            return view('frontend/account/account', compact('user', 'country', 'countries'));
        } else {
            return view('/frontend/member/login');
        }
    }

    public function post_update(UpdateProfileRequest $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        if ($request->email != $user->email) {
            return redirect()->back()->withErrors('email ko duoc thay doi');
        }
        if (!empty($request->name)) {
            $user->name = $request->name;
        }
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        } else {
            $user->password = $user->password;
        }
        $file = $request->avatar;
        // echo $file->getClientOriginalName();
        if (!empty($file)) {
            $user->avatar = $file->getClientOriginalName();
        }
        $country_name = $request->country;
        $country = Country::all();
        $check = 0;
        foreach ($country as $key => $value) {
            echo $country[$key]['id'] . "<br>";
            if ($country[$key]['name'] == $request->country) {
                $check = $country[$key]['id'];
            }
        }
        $user->country = $check;
        if ($user->save()) {
            if (!empty($file)) {
                $file->move('user_img', $file->getClientOriginalName());
            }
            return redirect()->back()->with('success', 'update profile success');
        } else {
            return redirect()->back()->withErrors(' update error');
        }
    }



    //product

    public function my_product()
    {


        $product = Product::select()->where('id_user',Auth::id())->orderBy('id', 'DESC')->paginate(3);


        // $getArrImage = [];
        // foreach($product as $key=>$value){
        // $getArrImage[] = json_decode($value['image'], true);
        // }

        return view('frontend/account/my-product', compact('product'));
    }
    public function add_product()
    {
        $categories = Category::select('id', 'name')->get()->toArray();
        $brands = Brand::select()->get()->toArray();
        return view('frontend/account/add-product', compact('categories', 'brands'));
    }
    public function post_product(AddProductRequest $request)
    {
        if (Auth::check()) {

            if (!isset($request->image)) {
                return redirect()->back()->withErrors('image khong duoc de trong');
            }
            if (count($request->file('image')) > 3) {
                return redirect()->back()->withErrors('khong dc qua 3 anh');
            } else {
                if ($request->hasfile('image')) {
                    $time = strtotime(date('Y-m-d H:i:s'));

                    foreach ($request->file('image') as $image) {
                        // echo $image->getClientOriginalName()."<br>";
                        $name = $time."--".$image->getClientOriginalName();
                        $name_2 = "w85"."--".$time."--".$image->getClientOriginalName();
                        $name_3 = "w329"."--".$time."--".$image->getClientOriginalName();
                        // $image->move('upload/product/', $name);
                        //  $name->width();
                        $path = public_path('upload/product/' . $name);
                        $path2 = public_path('upload/product/' . $name_2);
                        $path3 = public_path('upload/product/' . $name_3);

                        Image::make($image->getRealPath())->save($path);
                        Image::make($image->getRealPath())->resize(85, 84)->save($path2);
                        Image::make($image->getRealPath())->resize(329, 380)->save($path3);

                        $data[] = $name;
                    }
                    // foreach($request->file('image') as $image)
                    // {
                    //     // echo $image->getClientOriginalName()."<br>";

                    //     // $name = $time."-".$image->getClientOriginalName();
                    //     // $name_2 = "w85"."-".$time."-".$imaxge->getClientOriginalName();
                    //     // $name_3 = "w329".$image->getClientOriginalName();
                    //     $name = $image->getClientOriginalName();
                    //     $name_2 = "w85".$image->getClientOriginalName();
                    //     $name_3 = "w329".$image->getClientOriginalName();
                    //     // $image->move('upload/product/', $name);
                    //     //  $name->width();


                    //     // - khong biet hinh anh cua member nao
                    //     // - kiem tra folder nay co chua?
                    //     // - demo.png : iphone / demo.png: samsung

                    //     // w85_532532523_ten-hinh

                    //     $path = public_path('upload/product/1/' . $name);
                    //     $path2 = public_path('upload/product/1/' . $name_2);
                    //     $path3 = public_path('upload/product/1/' . $name_3);

                    //     Image::make($image->getRealPath())->save($path);
                    //     Image::make($image->getRealPath())->resize(85, 84)->save($path2);
                    //     Image::make($image->getRealPath())->resize(329, 380)->save($path3);

                    //     $data[] = $name;
                    // }
                }

                $product = new Product();
                $product->image = json_encode($data);

                $product->name = $request->name;
                $product->price = $request->price;
                $product->id_category = $request->category;
                $product->id_brand = $request->brand;
                if ($request->sale == 1) {
                    $product->sale = $request->number_sale;
                }
                $product->status = $request->sale;

                $product->company = $request->company_profile;
                $product->detail = $request->detail;
                $product->id_user = Auth::id();
                $product->save();
                // var_dump($product->toArray());
                // $inTest = Test::find(2)->toArray();
                // echo "<pre>";
                // $getArrImage = json_decode($inTest['name'], true);
                // echo $inTest['0']['name'];

                // echo "<pre>";
                // var_dump($getArrImage);

                return redirect('frontend/my_product');
            }
        } else {
            return redirect('frontend/login');
        }
    }

    public function product_detail(Request $request)
    {
        $product = Product::find($request->id)->toArray();
        // echo "<pre>";
        // var_dump($product);
        $getArrImage = json_decode($product['image'], true);
        // echo "<pre>";
        // var_dump($getArrImage);
        // echo $getArrImage[0];
        $brand = Brand::find($product['id_brand']);
        $category = Category::find($product['id_category']);

        //total_item
        $qty_arr = Cart::select('qty')->get()->toArray();
        $total_item = 0;
        foreach ($qty_arr as $key => $value) {
            $total_item += $value['qty'];
        }
        $carts = Cart::select()->get()->toArray();
        // echo "<pre>";
        // var_dump($category);
        return view('frontend/account/product-detail', compact('category', 'product', 'brand', 'getArrImage', 'total_item'));
    }
    public function edit_product(Request $request)
    {
        if (Auth::check()) {
            $product = Product::find($request->id)->toArray();
            $brands = Brand::select()->get()->toArray();
            $categories = Category::all()->toArray();

            $product_brand = [];
            foreach ($brands as $key => $value) {
                if ($value['id'] == $product['id_brand']) {
                    $product_brand[] = $value;
                    unset($brands[$key]);
                }
            }
            $product_cate = [];
            foreach ($categories as $key => $value) {
                if ($value['id'] == $product['id_category']) {
                    $product_cate[] = $value;
                    unset($categories[$key]);
                }
            }
            $product_brand = $product_brand['0'];
            $product_cate = $product_cate['0'];
            $getArrImage = json_decode($product['image'], true);
            // echo "<pre>";
            // var_dump($getArrImage);

            return view('frontend/account/edit_product', compact('product', 'brands', 'product_brand', 'categories', 'product_cate', 'getArrImage'));
        } else {
            return redirect('frontend/login');
        }
    }
    public function post_edit_product(AddProductRequest $request)
    {

        if (Auth::check()) {
            $image = Product::find($request->id)->toArray();
            $getArrImage = json_decode($image['image'], true);

            $img = [];
            if (isset($request->unimage)) {
                foreach ($request->unimage as $key => $value) {
                    $img[] = $key;
                }
            }
            $data = [];
            foreach ($getArrImage as $key => $value) {
                if (in_array($key, $img)) {
                    unset($getArrImage[$key]);
                } else {
                    $data[] = $value;
                }
            }

            if ($request->hasfile('image')) {
                $time = strtotime(date('Y-m-d H:i:s'));

                    foreach ($request->file('image') as $image) {
                        // echo $image->getClientOriginalName()."<br>";
                        $name = $time."--".$image->getClientOriginalName();
                        $name_2 = "w85"."--".$time."--".$image->getClientOriginalName();
                        $name_3 = "w329"."--".$time."--".$image->getClientOriginalName();
                        // $image->move('upload/product/', $name);
                        //  $name->width();
                        $path = public_path('upload/product/' . $name);
                        $path2 = public_path('upload/product/' . $name_2);
                        $path3 = public_path('upload/product/' . $name_3);

                        Image::make($image->getRealPath())->save($path);
                        Image::make($image->getRealPath())->resize(85, 84)->save($path2);
                        Image::make($image->getRealPath())->resize(329, 380)->save($path3);

                        $data[] = $name;

                }
            }
            if (count($data) > 3) {
                return redirect()->back()->withErrors('ko duoc qua 3 file anh');
            } else if (count($data) < 1) {
                return redirect()->back()->withErrors('image khong duoc de trong');
            }
            $product = Product::find($request->id);

            $product->name = $request->name;
            $product->price = $request->price;
            $product->id_category = $request->category;
            $product->id_brand = $request->brand;
            $product->status = $request->sale;
            if ($request->sale == "1") {
                $product->sale = $request->number_sale;
            } else {
                $product->sale = 0;
            }
            $product->company = $request->company_profile;
            $product->image = json_encode($data);
            if ($product->save()) {
                return redirect('frontend/my_product')->with('success', 'Edit thanh cong');
            }
        }
    }

    public function ajax_addtocart(Request $request)
    {
        $carts = Cart::all()->toArray();

        $check = true;

        foreach ($carts as $key => $value) {
            if ($value['id_product'] == $request->pro_id) {
                $check = false;
            }
        }
        if ($check == false) {

            $pro = Cart::select('id', 'qty')->where('id_product', $request->pro_id)
                ->get()->toArray();

            $id = $pro['0']['id'];
            $qty = $pro['0']['qty'];
            $qty++;
            $cart = Cart::find($id);
            $cart->qty = $qty;
        } else {
            $cart = new Cart;
            $cart->image = $request->pro_img;
            $cart->name = $request->pro_name;
            $cart->price = (int)$request->pro_price;

            $cart->id_product = $request->pro_id;
        }


        if (session()->has('carts_session')) {
            $check = true;
            $pro_id = $request->pro_id;
            $cart = session()->get('carts_session');
            foreach ($cart as $key => $value) {
                if ($pro_id == $value['product_id']) {
                    $cart[$key]['product_qty'] += 1;
                    $check = false;
                }
            }
            if ($check) {
                $carts = [];
                $carts = array(
                    "product_id" => (int)$request->pro_id,
                    "product_image" => $request->pro_img,
                    "product_name" => $request->pro_name,
                    "product_qty" => 1,
                    "product_price" => $request->pro_price
                );
                $carts_session = $carts;
                session()->push('carts_session', $carts_session);
            } else {
                session()->put('carts_session', $cart);
            }
        } else {
            $carts = [];
            $carts = array(
                "product_id" => (int)$request->pro_id,
                "product_image" => $request->pro_img,
                "product_name" => $request->pro_name,
                "product_qty" => 1,
                "product_price" => $request->pro_price
            );
            $carts_session = $carts;
            session()->push('carts_session', $carts_session);
        }



        //total_item
        if (session()->has('carts_session')) {
            $carts_session = session()->get('carts_session');
            $total_item = 0;
            foreach ($carts_session as $key => $value) {

                $total_item += $carts_session[$key]['product_qty'];
            }
            $total_item = (int)$total_item;
            session()->put('total_item', $total_item);
        }
        // session()->forget('carts_session');
        // session()->forget('total_item');

        if ($cart->save()) {
            return response()->json(['success' => "oke"]);
        } else {
            return response()->json(['errors' => 'failed']);
        }
    }
    public function lichsumuahang(Request $request)
    {
        // $cart = Cart::findOrFail(Auth::id())->toArray();

        $carts = Cart::where('id_user',Auth::id())->orderBy('id', 'DESC')->paginate(5);
        // $top = History::select('email','qty')->get()->toArray();

        // echo "<pre>";
        // var_dump($cart);
        // return view()
        return view('frontend/account/lichsumuahang',compact('carts'));
    }
    public function delete_product_s(Request $request)
    {
        // echo "ads";
        echo $request->id;
        $product = Product::findOrFail($request->id);
        if($product->delete()){
            return redirect()->back()->with('success','xoa thanh cong');

        }else{
            return redirect()->back()->withErrors('da xay ra loi');

        }
        // echo "<pre>";
        // var_dump($product);
    }
}
