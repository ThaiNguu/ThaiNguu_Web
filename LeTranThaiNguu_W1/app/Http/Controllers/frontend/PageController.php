<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $posts = Post::where('type', 'page')->where('status', 1)->get();
        return view("frontend.page", compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('type', 'page')
                    ->where('status', 1)
                    ->where('slug', $slug)
                    ->firstOrFail();

        $posts = Post::where('type', 'page')
                     ->where('status', 1)
                     ->get(); // You can pass all posts if needed for navigation

        return view("frontend.page-show", compact('post', 'posts')); // Assuming your view file is named `page-show.blade.php`
    }
}

