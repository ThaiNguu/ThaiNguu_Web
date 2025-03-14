<?php

namespace App\Http\Controllers\backend;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Category;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Category::where('status', '!=', 0)
            ->select('categories.id', 'categories.name', 'categories.slug', 'categories.image','description','categories.parent_id','categories.sort_order', 'categories.status')
            ->orderBy('created_at', 'desc')
            ->get();
            $htmlparentid = "";
            $htmlsortorder = "";
            foreach ($list as $items) {
                $htmlparentid .= "<option value='$items->id'>" . $items->name . "</option>";
                $htmlsortorder .= "<option value='" . ($items->sort_order + 1) . "'>Sau: " . $items->name . "</option>";    
            }
        return view("backend.category.index", compact('list','htmlparentid','htmlsortorder'));
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::of($request->name)->slug('-');
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->sort_order = $request->sort_order;
        //upload image
        if ($request->image){
            $exten = $request->file("image")->extension();
            if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                $filename = $category->slug . "." . $exten;
                $request->image->move(public_path("images/categorys"), "$filename");
                $category->image = "$filename";
            }
        }
        //end upload
        $category->status = $request->status;
        $category->created_at = date('Y-m-d H:i:s');
        $category->created_by = Auth::id() ?? 1;
        $category->save();
        return redirect()->route('admin.category.index');
    }catch (\Exception $e) {return redirect()->back();}
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if ($category == null)
        {
            return redirect()->route('admin.category.index');
        }
        return view("backend.category.show", compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        if($category==null)
        {
            return redirect()->route('admin.category.index');
        }
        $list = Category::where('status', '!=', 0)
            ->select('category.id', 'category.name', 'category.slug', 'category.image','description','category.parent_id','category.sort_order', 'category.status')
            ->orderBy('created_at', 'desc')
            ->get();
            $htmlparentid = "";
            $htmlsortorder = "";
            foreach ($list as $items) {
                if($category->parent_id == $items->id)
                {
                    $htmlparentid .= "<option selected value='$items->id'>" . $items->name . "</option>";
                }
                else
                {
                    $htmlparentid .= "<option value='$items->id'>" . $items->name . "</option>";
                }
                if($category->sort_order-1==$items->sort_order)
                {
                    $htmlsortorder .= "<option selected value='" . ($items->sort_order + 1) . "'>Sau: " . $items->name . "</option>"; 
                }
                    $htmlsortorder .= "<option value='" . ($items->sort_order + 1) . "'>Sau: " . $items->name . "</option>";      
            }
        return view("backend.category.edit", compact('list','category','htmlparentid','htmlsortorder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
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
        //upload image
        if ($request->image){
            $exten = $request->file("image")->extension();
            if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                $filename = $category->slug . "." . $exten;
                $request->image->move(public_path("images/categorys"), "$filename");
                $category->image = "$filename";
            }
        }
        //end upload
        $category->status = $request->status;
        $category->updated_at = date('Y-m-d H:i:s');
        $category->updated_by = Auth::id() ?? 1;
        $category->save();
        return redirect()->route('admin.category.index');
   
    }
    public function trash()
    {
        $list = Category::where('status', '=', 0)
            ->select('id', 'name', 'slug', 'category.image','status')
            ->orderBy('created_at', 'desc')
            ->get();
            return view("backend.category.trash",compact('list'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if($category==null)
        {
            return redirect()->route('admin.category.index');
        }
        $category->delete();
        return redirect()->route('admin.category.trash');
    }
    //status status = 1->2, 2->1
    public function status(string $id)
    {
        $category = Category::find($id);
        if($category==null)
        {
            return redirect()->route('admin.category.index');
        }
        $category->status = ($category->status==2)?1:2;
        $category->updated_at = date('Y-m-d H:i:s');
        $category->updated_by = Auth::id() ?? 1;
        $category->save();
        return redirect()->route('admin.category.index');
    }
    //status status = 0
    public function delete(string $id)
    {
    $category = Category::find($id);

    if ($category == null) {
        return redirect()->route('admin.category.index');
    }

    $category->status = 0;
    $category->updated_at = date('Ymd H:i:s');
    $category->updated_by = Auth::id() ?? 1;
    $category->save(); //Lưu

    return redirect()->route('admin.category.index');
    }
    
    public function restore(string $id)
    {
        $category = Category::find($id);
        if($category==null)
        {
            return redirect()->route('admin.category.index');
        }
        $category->status = 1;
        $category->updated_at = date('Y-m-d H:i:s');
        $category->updated_by = Auth::id() ?? 1;
        $category->save();
        return redirect()->route('admin.category.trash');
    }
}
