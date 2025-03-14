<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostResource;
class PostController extends Controller
{
    public function index()
    {
        $post = Post::all();
        return PostResource::collection($post);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::of($request->title)->slug('-');
        $post->detail = $request->detail;
        $post->description = $request->description;
        $post->type = $request->type;
        $post->topic_id = $request->topic_id;
        //upload image
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/posts'), $imageName);
            $post->image = $imageName;
        }
        //end upload
        $post->status = $request->status;
        $post->created_at = date('Y-m-d H:i:s');
        $post->created_by = Auth::id() ?? 1;
        $post->save();
        return new PostResource($post);
    }

    public function show($id)
    {
        $post = Post::find($id);
    if ($post) {
        // Trả về dữ liệu dạng JSON
        return response()->json($post, 200);
    }}
    public function getPostsByTopic(Request $request)
    {
        // Lấy topic_id từ query parameters
        $topicId = $request->query('topic_id');
    
        // Lấy các bài viết theo topic_id, type = 'post', và status = 1
        $posts = Post::where('topic_id', $topicId)
                    ->where('type', 'post')
                    ->where('status', 1)
                    ->get();
    
        // Kiểm tra nếu có bài viết
        if ($posts->isNotEmpty()) {
            return response()->json(['data' => $posts], 200);
        }
    
        // Nếu không tìm thấy bài viết
        return response()->json(['message' => 'No posts found for this topic'], 404);
    }
    
    public function edit(Post $post)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request data:', $request->all());
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
        $post->status = $request->status;
        $post->updated_at = date('Y-m-d H:i:s');
        $post->updated_by = Auth::id() ?? 1;
        $post->save();
        try {
            $post->save();
            Log::info('Post updated successfully:', $post->toArray());
            return response()->json(['message' => 'Post updated successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to update post:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update post: ' . $e->getMessage()], 500);
        }
    }
    public function uploadImage(Request $request, $id)
    {
        // Kiểm tra xem có nhận được file hay không
        if ($request->hasFile('image')) {
            // Log tên file và kích thước
            Log::info('Image file name: ' . $request->file('image')->getClientOriginalName());
            Log::info('Image file size: ' . $request->file('image')->getSize());
    
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }
    
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/posts'), $imageName);
            $post->image = $imageName;
            $post->save();
    
            return response()->json(['message' => 'Image uploaded successfully']);
        }
    
        // Log thông báo nếu không có file
        Log::info('No image file uploaded');
        return response()->json(['message' => 'No image uploaded'], 400);
    }
    public function destroy(Post $post)
    {
        $post->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->status = ($post->status == 2) ? 1 : 2;
        $post->save();

        return response()->json(['message' => 'Post status updated successfully']);
    }
    public function delete(string $id)
{
    $post = Post::find($id);
    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $post->status = 0; 
    $post->save();

    return response()->json(['message' => 'Post status updated successfully']);
}


}
