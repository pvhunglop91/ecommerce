<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\admin\UpdateProfileRequest;
use App\Http\Requests\admin\InsertCountryRequest;
use App\Http\Requests\admin\InsertBlogRequest;
use Illuminate\Foundation\Auth\User;
use App\Country;
use App\Blog;
use App\History;
use App\Cart;
use MicrosoftAzure\Storage\Blob\Models\Blob;
class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {

        return view('admin/dashboard/dashboard');
    }

    public function history()
    {
        $histories = History::orderBy('id', 'DESC')->paginate(5);
        $his = History::all()->toArray();
       
        $count = 0;
        $number = count($his);
        // echo $number;
        $top = array();
        // foreach($his as $key=>$value){
        //     $count = History::where('id_user',$value['id_user'])->count();
        //     echo $count."<br>";
        // }
        
        // echo "<pre>";
        // var_dump($his);
        // foreach($top3 as $key=>$value){
            
        // }
        // echo "<pre>";
        // var_dump($top);
        
    
        return view('admin/dashboard/history',compact('histories'));

    }
    public function history_detail(Request $request){
        $cart = Cart::select()->where('id_user',$request->id)->get()->toArray();
        // echo "<pre>";
        // var_dump($cart);
        // foreach($cart as $key=>$value){
        //     echo $value['id_product'];
        // }
        return view('admin/dashboard/history_detail',compact('cart'));
    }
  
    public function pages_profile(Request $request)
    {
        if(Auth::check()){
            $id = Auth::user()->id;
            $name = Auth::user()->name;
            $email = Auth::user()->email;
            $password = Auth::user()->password;
            $phone = Auth::user()->phone;
            $country_id = Auth::user()->country;
            $avatar =  Auth::user()->avatar;
            // $country = Country::find($country_id);
            $countries = Country::select('id','name')->get()->toArray();
            $country = "";
            foreach($countries as $key=>$value){
                if($countries[$key]['id']==$country_id){
                    $country=$countries[$key]['name'];
                    unset($countries[$key]);
                }
            }
            echo $country;
            // echo "<pre>";
            // var_dump($countries);
            // echo "</pre>";

            // foreach($countries as $key=>$value){
            //     echo $countries[$key]['id'];
            // }


            return view('admin/dashboard/pages_profile',compact('avatar','id','name','email','password','phone','countries','country'));
        }

    }
    public function update_profile(UpdateProfileRequest $request)
    {

        $userId = Auth::id();
        $user = User::findOrFail($userId);

        if($request->email!=$user->email){
            return redirect()->back()->withErrors('email ko duoc thay doi');
        }
        if(!empty($request->name)){
            $user->name=$request->name;
        }
        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }else{
            $user->password = $user->password;
        }
        $file = $request->avatar;
        // echo $file->getClientOriginalName();
        if(!empty($file)){
            $user->avatar = $file->getClientOriginalName();
        }
        $country_name = $request->country;
        $country = Country::all();
        $check = 0;
        foreach($country as $key=>$value){
            echo $country[$key]['id']."<br>";
            if($country[$key]['name']==$request->country){
                $check = $country[$key]['id'];
            }
        }
        $user->country = $check;
        if($user->save()){
            if(!empty($file)){
                $file->move('user_img',$file->getClientOriginalName());
            }
            return redirect()->back()->with('success','update profile success');
        }else{
            return redirect()->back()->withErrors(' update error');
        }



    }

    //country
    public function country(Request $request)
    {
        $countries  = Country::select('id','name')->get()->toArray();
        return view('admin/dashboard/country',compact('countries'));
    }

    public function delete_country(Request $request)
    {
        Country::findOrFail($request->id)->delete();
        return redirect()->back();
    }

    public function get_country(Request $request)
    {
        return view('admin/dashboard/add_country');
    }
    public function post_country(InsertCountryRequest $request)
    {
        $country = new Country;
        $country['name']=$request->country_name;
        $country->save();
        return redirect('admin/country')->with('success','Insert thanh cong');
    }




    public function form_basic()
    {
        return view('admin/dashboard/form_basic');
    }
    public function table_basic()
    {
        return view('admin/dashboard/table_basic');
    }
    public function icon_material()
    {
        return view('admin/dashboard/icon_material');
    }
    public function starter_kit()
    {
        return view('admin/dashboard/starter_kit');
    }
    public function error_404()
    {
        return view('admin/dashboard/error_404');
    }
}
