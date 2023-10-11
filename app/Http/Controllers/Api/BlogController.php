<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;
use App\Rate;
use App\Comments;
class BlogController extends Controller
{
    public function show(Request $request)
    {
        echo 123;
        // exit;
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
        // return view('frontend/blog/blog-single',compact('comment','blog','next','prev','max_next','min_prev','id','rate','vote','average'));
        return response()->json([
            'blog' => $blog,
            'comment'=>$comment,
            'vote'=>$vote
        ]);
    }
}
