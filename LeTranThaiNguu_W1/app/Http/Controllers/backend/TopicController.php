<?php

namespace App\Http\Controllers\backend;
use App\Models\Topic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Topic::where('status', '!=', 0)
            ->select('id', 'name', 'slug','description','sort_order','status')
            ->orderBy('created_at', 'desc')
            ->get();
            $htmlsortorder = "";
            foreach ($list as $items) {
                $htmlsortorder .= "<option value='$items->id'>Sau:" . $items->name . "</option>";     
            }
        return view("backend.topic.index", compact('list','htmlsortorder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicRequest $request)
    {
        try {
            $topic = new Topic();
            $topic->name = $request->name;
            $topic->slug = Str::of($request->name)->slug('-');
            $topic->sort_order = $request->sort_order;
            $topic->description = $request->description;
            $topic->status = $request->status;
            $topic->created_at = date('Y-m-d H:i:s');
            $topic->created_by = Auth::id() ?? 1;
            $topic->save();
            return redirect()->route('admin.topic.index');
        }catch (\Exception $e) {return redirect()->back();}
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $topic = Topic::find($id);
        if ($topic == null)
        {
            return redirect()->route('admin.topic.index');
        }
        return view("backend.topic.show", compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $topic = Topic::find($id);
        if ($topic == null) {
            return redirect()->route('admin.topic.index');
        }
        $list = Topic::where('status', '!=', 0)
            ->select('id', 'name', 'slug','description','sort_order','status')
            ->orderBy('created_at', 'desc')
            ->get();
            $htmlsortorder = "";
            foreach ($list as $item) {
                $selected = ($topic->sort_order == $item->sort_order) ? 'selected' : '';
                $htmlsortorder .= "<option value='" . $item->sort_order . "' $selected>Sau: " . $item->name . "</option>";
            }
        return view("backend.topic.edit", compact('list','htmlsortorder','topic'));
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicRequest $request, string $id)
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
            return redirect()->route('admin.topic.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $topic = Topic::find($id);
        if($topic==null)
        {
            return redirect()->route('admin.topic.index');
        }
        $topic->delete();
        return redirect()->route('admin.topic.trash');
    }
    public function status(string $id)
    {
        $topic = Topic::find($id);
        if($topic==null)
        {
            return redirect()->route('admin.topic.index');
        }
        $topic->status = ($topic->status==2)?1:2;
        $topic->updated_at = date('Y-m-d H:i:s');
        $topic->updated_by = Auth::id() ?? 1;
        $topic->save();
        return redirect()->route('admin.topic.index');
    }
    public function trash()
    {
        $list = Topic::where('status', '=', 0)
        ->select('id', 'name', 'slug','description','sort_order','status')
        ->orderBy('created_at', 'desc')
        ->get();
        return view("backend.topic.trash", compact('list'));
    }
    public function delete(string $id)
    {
    $topic = Topic::find($id);

    if ($topic == null) {
        return redirect()->route('admin.topic.index');
    }

    $topic->status = 0;
    $topic->updated_at = date('Ymd H:i:s');
    $topic->updated_by = Auth::id() ?? 1;
    $topic->save(); //Lưu
    return redirect()->route('admin.topic.index');
    }

    public function restore(string $id)
    {
        $topic = Topic::find($id);
        if($topic==null)
        {
            return redirect()->route('admin.topic.index');
        }
        $topic->status = 1;
        $topic->created_at = date('Y-m-d H:i:s');
        $topic->created_by = Auth::id() ?? 1;
        $topic->save();
        return redirect()->route('admin.topic.trash');
    }

}
