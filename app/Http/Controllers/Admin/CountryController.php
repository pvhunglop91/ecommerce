<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
class CountryController extends Controller
{
    public function edit_country(Request $request)
    {
        // echo $request->id;
        $country = Country::where('id',$request->id)->get()->toArray();
        $country = $country['0'];
        // echo "<pre>";
        // var_dump($country);
        // echo $country['name'];
        return view('admin/country/edit_country',compact('country'));
    }
    public function post_edit_country(Request $request){
        // $country = Country::where('id',$request->id)->get()->toArray();
        // $country = $country['0'];
        // $country = New Country;
        $country = Country::findOrFail($request->id);
        // echo "<pre>";
        // var_dump($country);
        $country->name = $request->country_name;
        // echo $request->country_name;
        if($country->save()){
            return redirect('admin/country')->with('success','edit thanh cong');
        }else{
            return redirect()->back()->withErrors('edit that bai');
            
        }
    }
}
