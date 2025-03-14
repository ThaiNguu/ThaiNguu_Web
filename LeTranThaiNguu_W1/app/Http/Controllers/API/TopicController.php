<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;
use App\Http\Resources\TopicResource;
class TopicController extends Controller
{
    public function index()
    {
        $topic = Topic::all();
        return TopicResource::collection($topic);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $topic = new Topic();
            $topic->name = $request->name;
            $topic->slug = Str::of($request->name)->slug('-');
            $topic->sort_order = $request->sort_order;
            $topic->description = $request->description;
            $topic->status = $request->status;
            $topic->created_at = date('Y-m-d H:i:s');
            $topic->created_by = Auth::id() ?? 1;
            $topic->save();
        return new TopicResource($topic);
    }

    public function show($id)
    {
        $topic = Topic::find($id);
    if ($topic) {
        // Trả về dữ liệu dạng JSON
        return response()->json($topic, 200);
    }}


    public function edit(Topic $topic)
    {
        //
    }

    public function update(Request $request, $id)
    {
       
        $topic = Topic::find($id);
        if($topic==null)
        {
            return redirect()->route('admin.topic.index');
        }
            $topic->name = $request->name;
            $topic->slug = Str::of($request->name)->slug('-');
            $topic->sort_order = $request->sort_order;
            $topic->description = $request->description;
            $topic->status = $request->status;
            $topic->updated_at = date('Y-m-d H:i:s');
            $topic->updated_by = Auth::id() ?? 1;
            $topic->save();
            try {
                $topic->save();
                Log::info('Topic updated successfully:', $topic->toArray());
                return response()->json(['message' => 'Topic updated successfully']);
            } catch (\Exception $e) {
                Log::error('Failed to update topic:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Failed to update topic: ' . $e->getMessage()], 500);
            }
        
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $topic = Topic::find($id);
        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], 404);
        }

        $topic->status = ($topic->status == 2) ? 1 : 2;
        $topic->save();

        return response()->json(['message' => 'Topic status updated successfully']);
    }
    public function delete(string $id)
{
    $topic = Topic::find($id);
    if (!$topic) {
        return response()->json(['message' => 'Topic not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $topic->status = 0; 
    $topic->save();

    return response()->json(['message' => 'Topic status updated successfully']);
}


}
