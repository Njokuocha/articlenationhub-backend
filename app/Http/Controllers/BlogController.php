<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

class BlogController extends Controller
{
    // fetch all article categories
    public function fetch_categories()
    {
        return Category::all();
    }

    // trigger new post - upload article
    public function action_triggerpost(Request $request)
    {
        $request->validate([
            "user_id" => "required|exists:users,id",
            "blogTitle" => "required",
            "blogContent" => "required|min:150",
            "blogImage" => "required|file|mimes:jpg,png,jpeg|max:5120", // max filesize of 5 MB
            "category" => "required|exists:categories,id"
        ]);

        if($request->hasFile('blogImage')){
            $path = $request->file('blogImage')->store('uploads/blogs', 'public');
        }

        $category = Category::find($request->input('category'));

        Blog::create([
            'user_id' => $request->input('user_id'),
            'blogTitle' => $request->input('blogTitle'),
            'blogContent' => $request->input('blogContent'),
            'blogImage' => "http://localhost:8000/storage/" . $path,
            'category' => $category->category,
            'cat_id' => $category->id,
        ]);

        return response()->json([
            'status' => 'success',
            'img_link' => "http://localhost:8000/storage/" . $path,
        ]);
    }

    // fetching user blogs
    public function fetch_userblogs(Request $request)
    {
        return User::find($request->user()->id)->blogs()->orderBy('created_at', 'desc')->get();
    }

    // delete user blog
    public function delete_blogs_blog(Blog $blog)
    {
        $blog->delete();
        return response()->noContent();
    }

    // fetch a blog
    public function fetch_blogs_blog(Blog $blog)
    {
        return $blog->load('user');
    }

    public function fetch_blogs(Request $request)
    {
        if($request->status === "update") return Blog::with('user')->orderBy('created_at', 'desc')->limit(10)->get()->shuffle();
        return Blog::with('user')->inRandomOrder()->get();
    }

    public function fetch_blog_category(Category $category, Request $request)
    {
        // if($request->limit) return $category->blogs()
        //                         ->with('user')
        //                         ->inRandomOrder()
        //                         ->limit($request->limit)
        //                         ->get();
        return $category->blogs()->with('user')->inRandomOrder()->get();
    }
}
