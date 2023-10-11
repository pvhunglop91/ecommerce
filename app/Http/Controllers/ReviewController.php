<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Players;

use Validator;
use Illuminate\Auth\Events\Validated;
use App\Http\Requests\TestRequest;
// use\App\Http\Requests\Test2Request;

class ReviewController extends Controller
{
    public function index()
    {
        // $players = DB::table('players')
        //                 ->get()->toArray();
        $players = Players::all();
        return view('review_query_builder/index',compact('players'));
    }
    public function get_insert()
    {
        return view('review_query_builder/insert');
    }
    public function post_insert(Request $request)
    {
        // DB::table('players')
        //             ->insert([
        //                 'name'=>$request->name,
        //                 'age'=>$request->age,
        //                 'national'=>$request->national,
        //                 'position'=>$request->position,
        //                 'salary'=>$request->salary]);
        $record = new Players();
        $record->name = $request->name;
        $record->age = $request->age;
        $record->national = $request->national;
        $record->position = $request->position;
        $record->salary = $request->salary;
        $record->save();
        return redirect('review_query_builder/');
    }

    public function delete(Request $request)
    {
        $id= $request->id;
        // DB::table('players')->where('id',$id)->delete();
        Players::where('id',$request->id)->delete();
        return redirect('review_query_builder/');
    }

    public function get_edit(Request $request)
    {

        $id= $request->id;
        $player=DB::table('players')
                    ->where('id',$id)
                    ->get();

        $player=$player[0];
        return view('review_query_builder/edit',compact('player'));
    }
    public function post_edit(Request $request)
    {



        $id= $request->id;
        // DB::table('players')
        //             ->where('id',$id)
        //             ->update([
        //                 'name'=>$request->name,
        //                 'age'=>$request->age,
        //                 'national'=>$request->national,
        //                 'position'=>$request->position,
        //                 'salary'=>$request->salary
        //             ]);
        $player =Players::find($id);
        $player->name = $request->name;
        $player->age = $request->age;
        $player->national = $request->national;
        $player->position = $request->position;
        $player->salary = $request->salary;
        $player->save();
        return redirect('review_query_builder/');
    }
    public function get_validation()
    {
        return view('review_query_builder/validation_vd');
    }
    public function post_validation(Test2Request $request)
    {
         echo $request->name;
        echo $request->age;
        // $this->validate($request,
        //     [
        //         'name'=>'required|min:5|max:255',
        //         'age'=>'required|integer|max:3',
        //     ],
        //     [
        //         'required'=>':attribute khong duoc de trong',
        //         'min'=>':attribute khong duoc nho hon :min',
        //         'max'=>':attribute khong duoc lon hon :max',
        //         'integer'=>':attribute chi duoc nhap so',
        //     ],
        //     [
        //         'name'=>'Tieu de',
        //         'age'=>'Tuoi',
        //     ]
        // );
        // $success = "thanh cong";
        // echo "okela";
        // $validate = Validator::make(
        //     $request->all(),
        //     [
        //         'name'=>'required|min:5|max:255',
        //         'age'=>'required|integer|max:3',
        //     ],
        //     [
        //         'required'=>':attribute khong duoc de trong',
        //         'min'=>':attribute khong duoc nho hon :min',
        //         'max'=>':attribute khong duoc lon hon :max',
        //         'integer'=>':attribute chi duoc nhap so',
        //     ],
        //     [
        //         'name'=>'Tieu de',
        //         'age'=>'Tuoi',
        //     ]
        // );
        // if($validate->fails()){
        // return redirect('validation')->withErrors($validate);
        // }

    }
}
