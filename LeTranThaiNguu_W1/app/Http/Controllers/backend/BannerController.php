<?php

namespace App\Http\Controllers\backend;
use App\Models\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Banner::where('status', '!=', 0)
            ->select('banner.id', 'banner.name', 'banner.link','banner.position', 'banner.image', 'banner.status')
            ->orderBy('created_at', 'desc')
            ->get();
            $htmlposition = "";
            foreach ($list as $items) {
                $htmlposition .= "<option value='$items->position'>Sau:" . $items->position . "</option>";     
            }
        return view("backend.banner.index", compact('list','htmlposition'));
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        try{
            $banner = new Banner();
            $banner->id = $request->id;
            $banner->name = $request->name;
            $banner->description = $request->description;
            $banner->link = $request->link;
            $banner->position = $request->position;
            //upload image
            if ($request->image){
                $exten = $request->file("image")->extension();
                if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                    $filename = $banner->name . "." . $exten;
                    $request->image->move(public_path("images/banners"), "$filename");
                    $banner->image = "$filename";
                }
            }
            //end upload
            $banner->status = $request->status;
            $banner->created_at = date('Y-m-d H:i:s');
            $banner->created_by = Auth::id() ?? 1;
            $banner->save();
            return redirect()->route('backend.banner.index');
        }catch (\Exception $e) {return redirect()->back();}
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::find($id);
        if ($banner == null)
        {
            return redirect()->route('admin.banner.index');
        }
        return view("backend.banner.show", compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::find($id);
        if ($banner == null) {
            return redirect()->route('admin.banner.index');
        }
        $list = Banner::where('status', '!=', 0)
            ->select('banner.id', 'banner.name', 'banner.link','banner.position', 'banner.image', 'banner.status')
            ->orderBy('created_at', 'desc')
            ->get();
            $htmlposition = "";
            foreach ($list as $items) {
                $htmlposition .= "<option value='$items->position'>Sau:" . $items->position . "</option>";     
            }
            return view("backend.banner.edit", compact('list', 'banner', 'htmlposition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, string $id)
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

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        $exten = $request->file("image")->extension();
        if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])) {
            $filename = $banner->name . "." . $exten;
            $request->image->move(public_path("images/banners"), $filename);
            $banner->image = $filename;
        }
    }

    $banner->status = $request->status;
    $banner->updated_at = now();
    $banner->updated_by = Auth::id() ?? 1;
    $banner->save();
    return redirect()->route('admin.banner.index');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::find($id);
        if($banner==null)
        {
            return redirect()->route('admin.banner.index');
        }
        $banner->delete();
        return redirect()->route('admin.banner.trash');
    }
    public function trash()
    {
        $list = Banner::where('status', '=', 0)
            ->select('banner.id', 'banner.name', 'banner.link','banner.position', 'banner.image', 'banner.status')
            ->orderBy('created_at', 'desc')
            ->get();
            return view("backend.banner.trash", compact('list'));
    }
    public function delete(string $id)
    {
    $banner = Banner::find($id);

    if ($banner == null) {
        return redirect()->route('admin.banner.index');
    }
    $banner->status = 0;
    $banner->updated_at = date('Ymd H:i:s');
    $banner->updated_by = Auth::id() ?? 1;
    $banner->save(); //Lưu

    return redirect()->route('admin.banner.index');
    }
    public function restore(string $id)
    {
        $banner = Banner::find($id);
        if($banner==null)
        {
            return redirect()->route('admin.banner.index');
        }
        $banner->status = 1;
        $banner->updated_at = date('Y-m-d H:i:s');
        $banner->updated_by = Auth::id() ?? 1;
        $banner->save();
        return redirect()->route('admin.banner.trash');
    }
    public function status(string $id)
    {
        $banner = Banner::find($id);
        if($banner==null)
        {
            return redirect()->route('admin.banner.index');
        }
        $banner->status = ($banner->status==2)?1:2;
        $banner->updated_at = date('Y-m-d H:i:s');
        $banner->updated_by = Auth::id() ?? 1;
        $banner->save();
        return redirect()->route('admin.banner.index');
    }
    
}
