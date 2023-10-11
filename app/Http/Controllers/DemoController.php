<?php

namespace App\Http\Controllers;
use App\News;
use App\Demo;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_info1(){
        // $players = DB::table('players')->get();

        // $db = DB::table('demo')->select('id')->get();
        // return $db;
        // dd($db);
        return view('bai_tap_query_builder/index');
    }





    //sql
     public function index()
    {
        $db = DB::table('demo')->get();
        dd($db);
        return $db;
    }
    public function get_column()
    {
        $db = DB::table('demo')->select('id')->get();
        // return $db;
        dd($db);
    }
    public function insert_record()
    {
        DB::table('demo')->insert(
            [
            'name' => 'Demo_insert',
            'email' => 'demo_insert@gmail.com',
            'password'=>'asdasd'
            ]
        );
        echo "da them san phan";
    }
    public function update_record()
    {
        DB::table('demo')
                    ->where('id',3)
                    ->update(['email'=>'demo_update@gmail.com']);
        echo "update thanh cong";
    }
    public function select_where()
    {
        //xuat ra id=1
        // echo("xuat ra id = 1");
        $db = DB::table('demo')->where('id','1')->get();
        // dd($db);
        // echo "show ra record co id >1";
        $db2 = DB::table('demo')->where('id','>','1')->get();
        // dd($db2);
        echo("show record co id>2 and password=asdasd.");
        $db3 = DB::table('demo')
                        ->where('id','>','1')
                        ->where('email','demo_insert@gmail.com')
                        ->get();
        dd($db3);
    }


    public function getlogin(){
        return view('login');
    }
    public function postlogin(Request $request){
        $email = $request->email;
        $pass = $request->pass;
        echo($email."...".$pass);
    }


    public function show_form(){
        $gender = 'male';
        return view('login');
    }
    public function get_Data_form(Request $request){
        echo $request->email."<br>";
        echo $request->pass;
    }

    public function show_form_c(){
        return view('test_sql');
    }
    public function create_table(Request $request){
        $email =  ($request->email);

        // echo("asd");
    }
    // public function lay_data(Request $request){
    //     // $request->
    //     echo("hi");
    // }
    // public function GetLogin()
    // {
    //     return view('login');
    // }
    // public function PostLogin(Request $request)
    // {
    //     echo $request->username."-----";
    //     echo $request->password;
    // }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function show(Demo $demo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function edit(Demo $demo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Demo $demo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Demo $demo)
    {
        //
    }
}
