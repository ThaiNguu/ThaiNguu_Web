<?php

namespace App\Http\Controllers\backend;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $list = Brand::where('status', '!=', 0)
            ->select('id', 'name', 'slug', 'image', 'status')
            ->orderBy('created_at', 'desc')
            ->get();
           
            $htmlsortorderbrand = "";
            foreach ($list as $items) {
                $htmlsortorderbrand .= "<option value='$items->id'>Sau:" . $items->name . "</option>";     
            }
        return view("backend.brand.index", compact('list','htmlsortorderbrand'));
    }

    
    public function store(StoreBrandRequest $request)
    {
        try{
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::of($request->name)->slug('-');
        $brand->description = $request->description;
        $brand->sort_order = $request->sort_order;
        //upload image
        if ($request->image){
            $exten = $request->file("image")->extension();
            if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                $filename = $brand->slug . "." . $exten;
                $request->image->move(public_path("images/brands"), "$filename");
                $brand->image = "$filename";
            }
        }
        //end upload
        $brand->status = $request->status;
        $brand->created_at = date('Y-m-d H:i:s');
        $brand->created_by = Auth::id() ?? 1;
        $brand->save();
        return redirect()->route('backend.brand.index');
    }catch (\Exception $e) {return redirect()->back();}
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);
        if ($brand == null)
        {
            return redirect()->route('admin.brand.index');
        }
        return view("backend.brand.show", compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $brand = Brand::find($id);
    if ($brand == null) {
        return redirect()->route('admin.brand.index');
    }
    
    $list = Brand::where('status', '!=', 0)
                 ->select('id', 'name', 'slug', 'image', 'status', 'sort_order')
                 ->orderBy('sort_order', 'asc')
                 ->get();
    
    $htmlsortorderbrand = "";
    
    foreach ($list as $item) {
        $selected = ($brand->sort_order == $item->sort_order) ? 'selected' : '';
        $htmlsortorderbrand .= "<option value='" . $item->sort_order . "' $selected>Sau: " . $item->name . "</option>";
    }
    
    return view("backend.brand.edit", compact('list', 'brand', 'htmlsortorderbrand'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, string $id)
    {
        $brand = Brand::find($id);
        if($brand==null)
        {
            return redirect()->route('admin.brand.index');
        }
        $brand->name = $request->name;
        $brand->slug = Str::of($request->name)->slug('-');
        $brand->description = $request->description;
        $brand->sort_order = $request->sort_order;
        //upload image
        if ($request->image){
            $exten = $request->file("image")->extension();
            if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                $filename = $brand->slug . "." . $exten;
                $request->image->move(public_path("images/brands"), "$filename");
                $brand->image = "$filename";
            }
        }
        //end upload
        $brand->status = $request->status;
        $brand->updated_at = date('Y-m-d H:i:s');
        $brand->updated_by = Auth::id() ?? 1;
        $brand->save();
        return redirect()->route('admin.brand.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if($brand==null)
        {
            return redirect()->route('admin.brand.index');
        }
        $brand->delete();
        return redirect()->route('admin.brand.trash');
    }
    public function trash()
    {
        $list = Brand::where('status', '=', 0)
        ->select('id', 'name', 'slug', 'image', 'status')
        ->orderBy('created_at', 'desc')
        ->get();
        return view("backend.brand.trash",compact('list'));
    }
    public function status(string $id)
    {
        $brand = Brand::find($id);
        if($brand==null)
        {
            return redirect()->route('admin.brand.index');
        }
        $brand->status = ($brand->status==2)?1:2;
        $brand->updated_at = date('Y-m-d H:i:s');
        $brand->updated_by = Auth::id() ?? 1;
        $brand->save();
        return redirect()->route('admin.brand.index');
    }
    public function restore(string $id)
    {
        $brand = Brand::find($id);
        if($brand==null)
        {
            return redirect()->route('admin.brand.index');
        }
        $brand->status = 1;
        $brand->updated_at = date('Y-m-d H:i:s');
        $brand->updated_by = Auth::id() ?? 1;
        $brand->save();
        return redirect()->route('admin.brand.trash');
    }

    public function delete(string $id)
    {
    $brand = Brand::find($id);

    if ($brand == null) {
        return redirect()->route('admin.brand.index');
    }
    $brand->status = 0;
    $brand->updated_at = date('Ymd H:i:s');
    $brand->updated_by = Auth::id() ?? 1;
    $brand->save(); //Lưu

    return redirect()->route('admin.brand.index');
    }
}
