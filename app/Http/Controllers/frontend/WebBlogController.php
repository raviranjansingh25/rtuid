<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class WebBlogController extends Controller
{
    public function web_blog()
    {
        $blog = Blog::where('status', 1)->get();
        $data = array(
            'title' => 'Blog',
            'blog' => $blog,

        );
        return view('frontend.blog')->with($data);
    }

    public function web_blog_detail($id)
    {
        $blog = Blog::where('status', 1)->where('slug', $id)->first();
        $blogs = Blog::where('status', 1)->limit(3)->orderBy('id', 'DESC')->get();
        $data = array(
            'title' => $blog->title,
            'blog' => $blog,
            'blogs' => $blogs,


        );
        return view('frontend.blog_detail')->with($data);
    }
}
