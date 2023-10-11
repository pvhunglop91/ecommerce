<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;
use App\Comments;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\admin\UpdateProfileRequest;
use App\Http\Requests\admin\InsertCountryRequest;
use App\Http\Requests\admin\InsertBlogRequest;


class BlogController extends Controller
{
    //Blog
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function blog(Request $request)

    {
        // $blogs = Blog::select('id','title','image','description','content')->paginate(2);
        $blogs = Blog::orderBy('id', 'desc')->paginate(3);
        return view('admin/dashboard/blog',compact('blogs'));
        // $users = DB::table('users')->paginate(15);

        // return view('user.index', ['users' => $users]);
    }
    public function add_blog()
    {

        return view('admin/dashboard/add_blog');
    }
    public function post_add_blog(InsertBlogRequest $request){
        $blog = new Blog;
        $file = $request->image;
        $blog->title = $request->title;
        $blog->image = $file->getClientOriginalName();
        $blog->description = $request->description;
        $blog->content = $request->content;
        $blog->save();
        if($blog->save()){
            if(!empty($file)){
                $file->move('blog_img',$file->getClientOriginalName());
            }
            return redirect('admin/blog');
        }else{
            return redirect()->back()->withErrors('Add blog failed');
            // echo "ad";
        }
    }
    public function delete_blog(Request $request)
    {
        Blog::findOrFail($request->id)->delete();
        return redirect()->back();
    }
    public function get_edit_blog(Request $request)
    {
        // echo $request->id;
         $blog =  Blog::findOrfail($request->id);


        // dd($blog);
        return view('admin/dashboard/edit_blog',compact('blog'));
    }

    public function post_edit_blog(InsertBlogRequest $request)
    {
        $blog = Blog::findOrFail($request->id);
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->content = $request->content;
        $file = $request->image;

        if(!empty($file)){
        $blog->image =  $file->getClientOriginalName();
        }

        if($blog->save()){
            return redirect('blog')->with('success',__('update blog thanh cong'));
        }
    }
    public function comment(){
        $comments = Comments::orderBy('id', 'desc')->paginate(5);
        return view('admin/blog/comment',compact('comments'));
    }
    public function delete_comment(Request $request){
         Comments::findOrfail($request->id)->delete();
        // $comment->delete();
        return redirect()->back()->with('success','delete thanh cong');
    }
}
