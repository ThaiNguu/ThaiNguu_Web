<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::join('categories', 'product.category_id', '=', 'categories.id')
            ->join('brands', 'product.brand_id', '=', 'brands.id')
            ->select(
                "product.id",
                "product.category_id",
                "product.image",
                "product.name",
                "categories.name as categoryname",
                "brands.name as brandname",
                "product.price",
                "product.pricesale",
                "product.status"
            )
            ->orderBy('product.created_at', 'desc')
            ->get();

        return ProductResource::collection($product);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name); // Tạo slug từ tên sản phẩm
        $product->detail = $request->detail;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->pricesale = $request->pricesale;
        $product->status = $request->status;


        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }
        $product->created_at = date('Y-m-d H:i:s');
        $product->created_by = Auth::id() ?? 1;

        $product->save();

        // Trả về phản hồi JSON với thông tin sản phẩm
        // return response()->json($product);

        return new ProductResource($product);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json($product, 200);
        }
        return response()->json(['message' => 'Product not found'], 404);
    }


    public function update(Request $request, $id)
    {
        Log::info('Update request data:', $request->all());

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Debugging data
        Log::info('Product before update:', $product->toArray());

        $product->name = $request->name;
        $product->slug = Str::of($product->name)->slug('-');
        $product->detail = $request->detail;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->pricesale = $request->pricesale;
        $product->status = $request->status;
        $product->status = $request->status;
        $product->updated_at = date('Y-m-d H:i:s');
        $product->updated_by = Auth::id() ?? 1;
        $product->save();

        try {
            $product->save();
            Log::info('Product updated successfully:', $product->toArray());
            return response()->json(['message' => 'Product updated successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to update product:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update product: ' . $e->getMessage()], 500);
        }
    }
    public function uploadImage(Request $request, $id)
    {
        // Kiểm tra xem có nhận được file hay không
        if ($request->hasFile('image')) {
            // Log tên file và kích thước
            Log::info('Image file name: ' . $request->file('image')->getClientOriginalName());
            Log::info('Image file size: ' . $request->file('image')->getSize());

            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
            $product->save();

            return response()->json(['message' => 'Image uploaded successfully']);
        }

        // Log thông báo nếu không có file
        Log::info('No image file uploaded');
        return response()->json(['message' => 'No image uploaded'], 400);
    }


    public function edit(Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->status = ($product->status == 2) ? 1 : 2;
        $product->save();

        return response()->json(['message' => 'Product status updated successfully']);
    }
    public function delete(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Đặt trạng thái thành 0 khi xóa
        $product->status = 0;
        $product->save();

        return response()->json(['message' => 'Product status updated successfully']);
    }
    
}
