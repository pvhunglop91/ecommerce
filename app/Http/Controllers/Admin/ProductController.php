<?php

namespace App\Http\Controllers\Admin;
use App\Product;
use App\Category;
use App\Brand;
use Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\frontend\AddProductRequest;


class ProductController extends Controller
{
    public function product(){
        $product = Product::orderBy('id', 'desc')->paginate(5);
        return view('admin/product/product',compact('product'));
    }
    public function add_product()
    {
        $categories = Category::select('id', 'name')->get()->toArray();
        $brands = Brand::select()->get()->toArray();
        return view('admin/product/add_product', compact('categories', 'brands'));
    }
    
    public function post_add_product(AddProductRequest $request){
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
               

                return redirect('admin/product');
            }
        } else {
            // return redirect('frontend/login');
        }
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

            return view('admin/product/edit_product', compact('product', 'brands', 'product_brand', 'categories', 'product_cate', 'getArrImage'));
        } else {
            // return redirect('frontend/login');
        }
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
