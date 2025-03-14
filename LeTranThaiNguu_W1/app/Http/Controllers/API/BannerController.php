<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BannerResource;
class BannerController extends Controller
{
    public function index()
    {
        $banner = Banner::all();
        return BannerResource::collection($banner);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $banner = new Banner();
            $banner->id = $request->id;
            $banner->name = $request->name;
            $banner->description = $request->description;
            $banner->link = $request->link;
            $banner->position = $request->position;
            //upload image
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('images/banners'), $imageName);
                $banner->image = $imageName;
            }
            //end upload
            $banner->status = $request->status;
            $banner->created_at = date('Y-m-d H:i:s');
            $banner->created_by = Auth::id() ?? 1;
            $banner->save();
        return new BannerResource($banner);
    }

    public function show($id)
    {
        $banner = Banner::find($id);
    if ($banner) {
        // Trả về dữ liệu dạng JSON
        return response()->json($banner, 200);
    }

    return response()->json(['message' => 'Category not found'], 404);
    }

    public function edit(Banner $banner)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);
    if ($banner == null) {
        return redirect()->route('admin.banner.index');
    }

    // Update the existing banner instance
    $banner->name = $request->name;
    $banner->description = $request->description;
    $banner->link = $request->link;
    $banner->position = $request->position;
    $banner->status = $request->status;
    $banner->updated_at = now();
    $banner->updated_by = Auth::id() ?? 1;
    $banner->save();
    }
    public function uploadImage(Request $request, $id)
    {
        // Kiểm tra xem có nhận được file hay không
        if ($request->hasFile('image')) {
            // Log tên file và kích thước
            Log::info('Image file name: ' . $request->file('image')->getClientOriginalName());
            Log::info('Image file size: ' . $request->file('image')->getSize());
    
            $banner = Banner::find($id);
            if (!$banner) {
                return response()->json(['message' => 'Banner not found'], 404);
            }
    
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/banners'), $imageName);
            $banner->image = $imageName;
            $banner->save();
    
            return response()->json(['message' => 'Image uploaded successfully']);
        }
    
        // Log thông báo nếu không có file
        Log::info('No image file uploaded');
        return response()->json(['message' => 'No image uploaded'], 400);
    }
    public function status(string $id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        $banner->status = ($banner->status == 2) ? 1 : 2;
        $banner->save();

        return response()->json(['message' => 'Banner status updated successfully']);
    }
    public function delete(string $id)
{
    $banner = Banner::find($id);
    if (!$banner) {
        return response()->json(['message' => 'Banner not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $banner->status = 0; 
    $banner->save();

    return response()->json(['message' => 'Banner status updated successfully']);
}

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response(null, 204);
    }
}
