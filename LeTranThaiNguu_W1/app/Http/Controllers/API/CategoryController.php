<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Product;
use App\Http\Resources\CategoryResource;
class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return CategoryResource::collection($category);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::of($request->name)->slug('-');
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->sort_order = $request->sort_order;
        //upload image
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/categorys'), $imageName);
            $category->image = $imageName;
        }
        //end upload
        $category->status = $request->status;
        $category->created_at = date('Y-m-d H:i:s');
        $category->created_by = Auth::id() ?? 1;
        $category->save();
        return new CategoryResource($category);
    }

    public function show($id)
    {
        $category = Category::find($id);
    if ($category) {
        // Trả về dữ liệu dạng JSON
        return response()->json($category, 200);
    }

    return response()->json(['message' => 'Category not found'], 404);
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request data:', $request->all());
        $category = Category::find($id);
        if($category==null)
        {
            return redirect()->route('admin.category.index');
        }
        $category->name = $request->name;
        $category->slug = Str::of($request->name)->slug('-');
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->sort_order = $request->sort_order;
        $category->status = $request->status;
        $category->updated_at = date('Y-m-d H:i:s');
        $category->updated_by = Auth::id() ?? 1;
        $category->save();
        try {
            $category->save();
            Log::info('Category updated successfully:', $category->toArray());
            return response()->json(['message' => 'Category updated successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to update category:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update category: ' . $e->getMessage()], 500);
        }
    }
    public function uploadImage(Request $request, $id)
    {
        // Kiểm tra xem có nhận được file hay không
        if ($request->hasFile('image')) {
            // Log tên file và kích thước
            Log::info('Image file name: ' . $request->file('image')->getClientOriginalName());
            Log::info('Image file size: ' . $request->file('image')->getSize());
    
            $category = Category::find($id);
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
    
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/categorys'), $imageName);
            $category->image = $imageName;
            $category->save();
    
            return response()->json(['message' => 'Image uploaded successfully']);
        }
    
        // Log thông báo nếu không có file
        Log::info('No image file uploaded');
        return response()->json(['message' => 'No image uploaded'], 400);
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->status = ($category->status == 2) ? 1 : 2;
        $category->save();

        return response()->json(['message' => 'Category status updated successfully']);
    }
    public function getProductsByCategory($slug)
{
    // Lấy danh mục dựa trên slug
    $category = Category::where('slug', $slug)->first();

    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    // Lấy danh sách các danh mục con
    $subCategories = Category::where('parent_id', $category->id)->pluck('id')->toArray();

    // Lấy sản phẩm theo category_id và các subcategory, chỉ lấy sản phẩm có status = 1
    $products = Product::where(function($query) use ($category, $subCategories) {
        $query->where('category_id', $category->id)
              ->orWhereIn('category_id', $subCategories);
    })
    ->where('status', 1) // Chỉ lấy sản phẩm đang hiển thị
    ->get();

    return response()->json([
        'category' => new CategoryResource($category),
        'products' => $products,
    ]);
}



    public function delete(string $id)
{
    $category = Category::find($id);
    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $category->status = 0; 
    $category->save();

    return response()->json(['message' => 'Category status updated successfully']);
}


}
