<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Topic;

class PostController extends Controller
{
    // Get all posts
    public function index()
    {
        $list_post = Post::where('status', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        $topics = Topic::where('status', '=', 1)->get();
        return view("frontend.post", compact('list_post', 'topics'));
    }

    // Get posts by topic
    public function topic($slug)
    {
        $topic = Topic::where([['slug', '=', $slug], ['status', '=', 1]])->first();
        if ($topic) {
            $list_post = Post::where([['status', '=', 1], ['topic_id', '=', $topic->id]])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            $topics = Topic::where('status', '=', 1)->get(); // Danh sách chủ đề
            $new_posts = Post::where('status', '=', 1)->orderBy('created_at', 'desc')->limit(5)->get(); // Bài viết mới
            return view("frontend.post_topic", compact('list_post', 'topic', 'topics', 'new_posts'));
        } else {
            return redirect()->route('site.post')->with('error', 'Topic not found');
        }
    }

    // Get post details
    public function post_detail($slug)
    {
        $post = Post::where([['status', '=', 1], ['slug', '=', $slug]])->first();
        $related_posts = Post::where([['status', '=', 1], ['id', '!=', $post->id], ['topic_id', '=', $post->topic_id]])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
        return view("frontend.post_detail", compact('post', 'related_posts'));
    }
}
