<?php

namespace App\Http\Controllers\backend;
use App\Models\Post;
use App\Models\Topic;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Post::where('status', '!=', 0)
        ->select('id', 'title', 'slug','detail', 'image','type', 'status')
        ->orderBy('created_at', 'desc')
        ->get();
    return view("backend.post.index", compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $topics = Topic::where('status', '!=', 0)
        ->select('topic.id', 'name')
        ->get();
        return view("backend.post.create",compact('topics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::of($request->title)->slug('-');
        $post->detail = $request->detail;
        $post->description = $request->description;
        $post->type = $request->type;
        $post->topic_id = $request->topic_id;
        //upload image
        if ($request->image){
            $exten = $request->file("image")->extension();
            if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                $filename = $post->slug . "." . $exten;
                $request->image->move(public_path("images/posts"), "$filename");
                $post->image = "$filename";
            }
        }
        //end upload
        $post->status = $request->status;
        $post->created_at = date('Y-m-d H:i:s');
        $post->created_by = Auth::id() ?? 1;
        $post->save();
        return redirect()->route('admin.post.index');
    } catch (\Exception $e) {return redirect()->back();
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        if ($post == null)
        {
            return redirect()->route('admin.post.index');
        }
        return view("backend.post.show", compact('post'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $topics = Topic::where('status', '!=', 0)
        ->select('topic.id', 'name')
        ->get();
        $post = Post::find($id);
        if ($post == null) {
        return redirect()->route('admin.post.index');
        }
        $list = Post::where('status', '!=', 0)
        ->select('id', 'title', 'slug','detail', 'image','type', 'status')
        ->orderBy('created_at', 'desc')
        ->get();
    return view("backend.post.edit", compact('list','post','topics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::find($id);
        if($post==null)
        {
            return redirect()->route('admin.post.index');
        }
        $post->title = $request->title;
        $post->slug = Str::of($request->title)->slug('-');
        $post->detail = $request->detail;
        $post->description = $request->description;
        $post->type = $request->type;
        $post->topic_id = $request->topic_id;
        //upload image
        if ($request->image){
            $exten = $request->file("image")->extension();
            if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                $filename = $post->slug . "." . $exten;
                $request->image->move(public_path("images/posts"), "$filename");
                $post->image = "$filename";
            }
        }
        //end upload
        $post->status = $request->status;
        $post->updated_at = date('Y-m-d H:i:s');
        $post->updated_by = Auth::id() ?? 1;
        $post->save();
        return redirect()->route('admin.post.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if($post==null)
        {
            return redirect()->route('admin.post.index');
        }
        $post->delete();
        return redirect()->route('admin.post.trash');
    }
    public function status(string $id)
    {
        $post = Post::find($id);
        if($post==null)
        {
            return redirect()->route('admin.post.index');
        }
        $post->status = ($post->status==2)?1:2;
        $post->updated_at = date('Y-m-d H:i:s');
        $post->updated_by = Auth::id() ?? 1;
        $post->save();
        return redirect()->route('admin.post.index');
    }
    public function trash()
    {
        $list = Post::where('status', '=', 0)
        ->select('id', 'title', 'slug','detail', 'image','type', 'status')
        ->orderBy('created_at', 'desc')
        ->get();
    return view("backend.post.trash", compact('list'));
    }

    public function restore(string $id)
    {
        $post = Post::find($id);
        if($post==null)
        {
            return redirect()->route('admin.post.index');
        }
        $post->status = 1;
        $post->created_at = date('Y-m-d H:i:s');
        $post->created_by = Auth::id() ?? 1;
        $post->save();
        return redirect()->route('admin.post.trash');
    }
    public function delete(string $id)
    {
    $post = Post::find($id);

    if ($post == null) {
        return redirect()->route('admin.post.index');
    }

    $post->status = 0;
    $post->updated_at = date('Ymd H:i:s');
    $post->updated_by = Auth::id() ?? 1;
    $post->save(); //Lưu

    return redirect()->route('admin.post.index');
    }
}
