<?php

namespace App\Http\Controllers;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class QlctController extends Controller
{
    public function set_info(Request $request)
    {

    }

    public function get_info1(){

        // $players = DB::table('players')->get()->toArray();
        // echo("ASd");
        // dd($players);
        // $players=['1','2','3'];
        // echo gettype($players);
        $players = News::all();
        // dd($players);
        return view('bai_tap_query_builder/index',compact('players'));
    }
    public function get_insert(){
        return view('bai_tap_query_builder/add');

    }
    public function post_insert(Request $request){
        $name = $request->name;
        $age = $request->age;
        $national = $request->national;
        $position = $request->position;
        $salary = $request->salary;
        // DB::table('players')->insert(
        //     ['name'=>$name,
        //      'age'=>$age,
        //      'national'=>$national,
        //       'position'=>$position,
        //       'salary'=>$salary]);
        // echo "insert thanh cong";
        $news = new News();
        $news->name = $name;
        $news->age= $age;
        $news->national=$national;
        $news->position = $position;
        $news->salary = $salary;
        $news->save();
        // $players = DB::table('players')->get()->toArray();
        return redirect('index2');

    }
    public function delete(Request $request){
        $id = $request->id;
        News::where('id',$id)->delete();
        // DB::table('players')->where('id',$id)->delete();
        return redirect('index2');
    }

    public function get_edit(Request $request){
        $id= $request->id;
        $player = DB::table('players')
                    ->where('id',$id)
                    ->get()->toArray();
        $player = $player[0];

        return view('bai_tap_query_builder/edit',compact('player'));
    }
    public function post_edit(Request $request){
        $name= $request->name;
        $age = $request->age;
        $national=$request->national;
        $position=$request->position;
        $salary=$request->salary;
        $id=$request->id;
        // echo ($id);
        // DB::table('players')
        //         ->where('id',$id)
        //         ->update(['name'=>$name,'age'=>$age,'national'=>$national,
        //         'position'=>$position,'salary'=>$salary]);

        $new = News::find($id);
        $new->name=$name;
        $new->age = $age;
        $new->national=$national;
        $new->position=$position;
        $new->salary=$salary;
        $new->save();
        return redirect('index2');
    }

}



