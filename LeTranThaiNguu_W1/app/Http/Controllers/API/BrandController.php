<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Http\Resources\BrandResource;
class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::all();
        return BrandResource::collection($brand);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::of($request->name)->slug('-');
        $brand->description = $request->description;
        $brand->sort_order = $request->sort_order;
        //upload image
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/brands'), $imageName);
            $brand->image = $imageName;
        }
        //end upload
        $brand->status = $request->status;
        $brand->created_at = date('Y-m-d H:i:s');
        $brand->created_by = Auth::id() ?? 1;
        $brand->save();

        // Trả về phản hồi JSON với thông tin sản phẩm
        // return response()->json($brand);

        return new BrandResource($brand);
    }

    public function show($id)
{
    $brand = Brand::find($id);
    if ($brand) {
        // Trả về dữ liệu dạng JSON
        return response()->json($brand, 200);
    }

    return response()->json(['message' => 'Brand not found'], 404);
}


    public function edit(Brand $brand)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request data:', $request->all());
        $brand = Brand::find($id);
        if($brand==null)
        {
            return redirect()->route('admin.brand.index');
        }
        $brand->name = $request->name;
        $brand->slug = Str::of($request->name)->slug('-');
        $brand->description = $request->description;
        $brand->sort_order = $request->sort_order;
        $brand->status = $request->status;
        $brand->updated_at = date('Y-m-d H:i:s');
        $brand->updated_by = Auth::id() ?? 1;
        $brand->save();
        try {
            $brand->save();
            Log::info('Brand updated successfully:', $brand->toArray());
            return response()->json(['message' => 'Brand updated successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to update brand:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update brand: ' . $e->getMessage()], 500);
        }
        
    }
    public function uploadImage(Request $request, $id)
    {
        // Kiểm tra xem có nhận được file hay không
        if ($request->hasFile('image')) {
            // Log tên file và kích thước
            Log::info('Image file name: ' . $request->file('image')->getClientOriginalName());
            Log::info('Image file size: ' . $request->file('image')->getSize());
    
            $brand = Brand::find($id);
            if (!$brand) {
                return response()->json(['message' => 'Brand not found'], 404);
            }
    
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/brands'), $imageName);
            $brand->image = $imageName;
            $brand->save();
    
            return response()->json(['message' => 'Image uploaded successfully']);
        }
    
        // Log thông báo nếu không có file
        Log::info('No image file uploaded');
        return response()->json(['message' => 'No image uploaded'], 400);
    }
    
    
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $brand->status = ($brand->status == 2) ? 1 : 2;
        $brand->save();

        return response()->json(['message' => 'Brand status updated successfully']);
    }
    public function delete(string $id)
{
    $brand = Brand::find($id);
    if (!$brand) {
        return response()->json(['message' => 'Brand not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $brand->status = 0; 
    $brand->save();

    return response()->json(['message' => 'Brand status updated successfully']);
}


}
