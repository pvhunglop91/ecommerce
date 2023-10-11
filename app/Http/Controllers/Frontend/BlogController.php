<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Blog;
use App\Rate;
use App\Comments;
use App\User;
class BlogController extends Controller
{
    // public function ajaxRequest()
    // {
    //     return view('frontend/blog/ajaxRequest');
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function blog_singlePost(Request $request)
    {

        $input = $request->Values;
        // $rates = Rate::all();
        $rate = new Rate;
        $rate->blog_id = $request->blog_id;
        $rate->qty = $request->Values;

        $id = $request->blog_id;
        // \Log::info($input);
        // echo "asd";
        // $asd->toArray();
        if($rate->save()){
            $rates = Rate::all();
            // $check = gettype($rates);
            $average = 0;
            $qty =0;
            $vote=0;
            foreach($rates as $key=>$value){
                $qty+=$value['qty'];
                $vote++;
            }
            $average = round($qty/$vote);
            $a = $qty/$vote;
            return response()->json(['success'=>$a,'vote'=>$vote,'average'=>$average]);

            }else{
            return response()->json(['errors'=>'error']);
        }

        // echo "Asdasd";
        // return response()->with('success')
    }
    public function blog(Request $request)

    {
        // $blogs = Blog::select('id','title','image','description','content')->paginate(2);
        $blogs = Blog::paginate(3);
        return view('frontend/blog/blog',compact('blogs'));
        // $users = DB::table('users')->paginate(15);

        // return view('user.index', ['users' => $users]);
    }
    public function blog_single(Request $request)

    {
        // $blogs = Blog::select('id','title','image','description','content')->paginate(2);
        $blog = Blog::findOrFail($request->id);
        $blog_all = Blog::all();

        // get previous user id
        $prev = Blog::where('id', '<', $request->id)->max('id');

        // get next user id
        $next = Blog::where('id', '>', $request->id)->min('id');
        $max_next = Blog::max('id');
        $min_prev = Blog::min('id');
        $id = $request->id;
        $rate = Rate::where('blog_id',$request->id)->get();

        $average = 0;
        $qty =0;
        $vote=0;
        $comment = Comments::select()->where('blog_id',$id)->orderby('level','ASC')->get()->toArray();
        foreach($rate as $key=>$value){
            // echo $rate[$key]."<br>";
            $vote++;
            $qty += $rate[$key]['qty'];
        }
        if($vote>0){
        $average = round($qty/$vote);
        }
        return view('frontend/blog/blog-single',compact('comment','blog','next','prev','max_next','min_prev','id','rate','vote','average'));
    }

    public function comment(Request $request)
    {
        // echo $request->message."<br>";
        // echo $request->id;
        // echo Auth::id();
        if(Auth::check()){
            $comment = new Comments;
            $comment->blog_id = $request->id;
            $comment->user_id = Auth::id();
            $comment->content = $request->message;
            $comment->avatar = User::find(Auth::id())->avatar;
            $comment->name = User::find(Auth::id())->name;


            if($comment->save()){

                return redirect()->back()->with('success','comment success');
            }
        }else{
            return view('/frontend/login');
        }

        // dd($comment);
        // return response()->json(['asd'=>'asdasdasd']);

    }
    public function rep_comment(Request $request)
    {
        echo Auth::id()."<br>";
        echo $request->id."<br>";
        echo $request->rep_cmt."<br>";
        echo $request->message."<br>";

        $comment = new Comments;
        $comment->blog_id = $request->id;
        $comment->user_id = Auth::id();
        $comment->content = $request->message;
        $comment->avatar = User::find(Auth::id())->avatar;
        $comment->name = User::find(Auth::id())->name;
        // echo $name;
        $file = $comment->avatar;
        $comment->level = $request->rep_cmt;
        if($comment->save()){
            // $file->move('',$file->getClientOriginalName());
            return redirect()->back()->with('success','rep comment success');
        }else{
            return redirect()->back()->withErrors('rep comment failed');
        }

    }
}
