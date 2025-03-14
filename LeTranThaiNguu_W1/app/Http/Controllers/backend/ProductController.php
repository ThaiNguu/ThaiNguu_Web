<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Product::where('product.status', '!=', 0)
        ->join('categories', 'product.category_id', '=', 'categories.id')
        ->join('brands', 'product.brand_id', '=', 'brands.id')
        ->select(
        "product.id",
        "product.image",
        "product.name",
        "categories.name as categoryname",
        "brands.name as brandname",
        "product.price",
        "product.status"
    )
    ->orderBy('product.created_at', 'desc')
    ->get();
    return view("backend.product.index", compact('list'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $categories = Category::where('status', '!=', 0)
        ->select('id', 'name')
        ->get();
    $brands = Brand::where('status', '!=', 0)
        ->select('id', 'name')
        ->get();   
    return view("backend.product.create", compact('categories', 'brands'));
    }

    
    public function store(StoreProductRequest $request)
    {
        try {
            $product = new Product();

            $product->name = $request->name;
            $product->slug = Str::of($product->name)->slug('-');
            $product->detail = $request->detail;
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->pricesale = $request->pricesale;
            $product->status = $request->status;

            // Upload image
            if ($request->hasFile('image')) {
                $exten = $request->file('image')->extension();
                if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])) {
                    $filename = Str::slug($product->name) . "." . $exten;
                    $request->image->move(public_path("images/products"), $filename);
                    $product->image = $filename;
                } else {
                    return back()->withErrors(['image' => 'Loại file không hợp lệ, chỉ chấp nhận jpg, png, gif, webp']);
                }
            }

            $product->created_at = date('Y-m-d H:i:s');
            $product->created_by = Auth::id() ?? 1;
            $product->save();

            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {return redirect()->back();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if ($product == null)
        {
            return redirect()->route('admin.product.index');
        }
        return view("backend.product.show", compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::where('status', '!=', 0)
        ->select('id', 'name')
        ->get();
        $brands = Brand::where('status', '!=', 0)
        ->select('id', 'name')
        ->get();   
        
        $product = Product::find($id);
        if($product==null)
        {
            return redirect()->route('admin.category.index');
        }
        $list = Product::where('product.status', '!=', 0)
        ->join('categories', 'product.category_id', '=', 'category.id')
        ->join('brands', 'product.brand_id', '=', 'brand.id')
        ->select(
            "product.id",
            "product.image",
            "product.name",
            "categories.name as categoryname",
            "brand.name as brandname",
            "product.price",
            "product.status"
        )
        ->orderBy('product.created_at', 'desc')
        ->get();
    
        return view("backend.product.edit", compact('list','product','categories','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        
        $product = Product::find($id);
        if ($product == null)
        {
            return redirect()->route('admin.product.index');
        }
        $product->name = $request->name;
            $product->slug = Str::of($product->name)->slug('-');
            $product->detail = $request->detail;
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->pricesale = $request->pricesale;
            $product->status = $request->status;

            // Upload image
            if ($request->hasFile('image')) {
                $exten = $request->file('image')->extension();
                if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])) {
                    $filename = Str::slug($product->name) . "." . $exten;
                    $request->image->move(public_path("images/products"), $filename);
                    $product->image = $filename;
                } else {
                    return back()->withErrors(['image' => 'Loại file không hợp lệ, chỉ chấp nhận jpg, png, gif, webp']);
                }
            }

            $product->status = $request->status;
            $product->updated_at = date('Y-m-d H:i:s');
            $product->updated_by = Auth::id() ?? 1;
            $product->save();  
            return redirect()->route('admin.product.index');
   
    }

    public function trash()
    {
        $list = Product::where('product.status', '=', 0)
    ->join('categories', 'product.category_id', '=', 'category.id')
    ->join('brands', 'product.brand_id', '=', 'brand.id')
    ->select(
        "product.id",
        "product.image",
        "product.name",
        "categories.name as categoryname",
        "brand.name as brandname",
        "product.price","product.slug",
        "product.status"
    )
    ->orderBy('product.created_at', 'desc')
    ->get();
            return view("backend.product.trash",compact('list'));
    }

    public function delete(string $id)
    {
    $product = Product::find($id);

    if ($product == null) {
        return redirect()->route('admin.product.index');
    }

    $product->status = 0;
    $product->updated_at = date('Ymd H:i:s');
    $product->updated_by = Auth::id() ?? 1;
    $product->save(); //Lưu

    return redirect()->route('admin.product.index');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if($product==null)
        {
            return redirect()->route('admin.product.index');
        }
        $product->delete();
        return redirect()->route('admin.product.trash');
    }

    public function restore(string $id)
    {
        $product = Product::find($id);
        if($product==null)
        {
            return redirect()->route('admin.product.index');
        }
        $product->status = 1;
        $product->updated_at = date('Y-m-d H:i:s');
        $product->updated_by = Auth::id() ?? 1;
        $product->save();
        return redirect()->route('admin.product.trash');
    }
    public function status(string $id)
    {
        $product = Product::find($id);
        if($product==null)
        {
            return redirect()->route('admin.product.index');
        }
        $product->status = ($product->status==2)?1:2;
        $product->updated_at = date('Y-m-d H:i:s');
        $product->updated_by = Auth::id() ?? 1;
        $product->save();
        return redirect()->route('admin.product.index');
    }
    
}