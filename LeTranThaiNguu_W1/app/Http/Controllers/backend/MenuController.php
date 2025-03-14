<?php

namespace App\Http\Controllers\backend;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Topic;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateMenuRequest;

class MenuController extends Controller
{
    public function index()
    {
        $list = Menu::where('status', '!=', 0)
            ->select("id", "link", "name", "position", "type", "status")
            ->orderBy('created_at', 'desc')
            ->get();
            $htmlparentidmenu = "";
            $htmlsortordermenu = "";
            foreach ($list as $items) {
                $htmlparentidmenu .= "<option value='$items->id'>" . $items->name . "</option>";
                $htmlsortordermenu .= "<option value='" . ($items->sort_order + 1) . "'>Sau: " . $items->name . "</option>";    
            }
        
        $list_category = Category::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();
        $list_brand = Brand::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();
        $list_topic = Topic::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();
        $list_page = Post::where([['status', '!=', 0], ['type', '=', 'page']])
            ->select("id", "title")
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.menu.index", compact('list', 'list_category', 'list_brand', 'list_topic', 'list_page','htmlparentidmenu','htmlsortordermenu'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {

        if (isset($request->createCategory)) {
            $listid = $request->categoryid;
            if ($listid) {
                foreach ($listid as $id) {
                    $category = Category::find($id);
                    if ($category != null) {
                        $menu = new Menu();
                        $menu->name = $category->name;
                        $menu->link = 'danh-muc/' . $category->slug;
                        $menu->sort_order = $category->sort_order; // Use existing sort_order
                        $menu->parent_id = $category->parent_id; // Use existing parent_id
                        $menu->type = 'category';
                        $menu->position = $request->position;
                        $menu->table_id = $id;
                        $menu->created_at = date('Y-m-d H:i:s');
                        $menu->created_by = Auth::id() ?? 1;
                        $menu->status = $request->status;
                        $menu->save();
                    }
                }
                return redirect()->route('admin.menu.index');
            } else {
                return redirect()->route('admin.menu.index')->with('error', 'Không có danh mục nào được chọn');
            }
        }

        if (isset($request->createBrand)) {
            $listid = $request->brandid;
            if ($listid) {
                foreach ($listid as $id) {
                    $brand = Brand::find($id);
                    if ($brand != null) {
                        $menu = new Menu();
                        $menu->name = $brand->name;
                        $menu->link = 'thuong-hieu/' . $brand->slug;
                        $menu->sort_order = $brand->sort_order; // Use existing sort_order
                        $menu->parent_id = $brand->parent_id;
                        $menu->type = 'brand';
                        $menu->position = $request->position;
                        $menu->table_id = $id;
                        $menu->created_at = date('Y-m-d H:i:s');
                        $menu->created_by = Auth::id() ?? 1;
                        $menu->status = $request->status;
                        $menu->save();
                    }
                }
                return redirect()->route('admin.menu.index');
            } else {
                return redirect()->route('admin.menu.index')->with('error', 'Không có thương hiệu nào được chọn');
            }
        }

        if (isset($request->createTopic)) {
            $listid = $request->topicid;
            if ($listid) {
                foreach ($listid as $id) {
                    $topic = Topic::find($id);
                    if ($topic != null) {
                        $menu = new Menu();
                        $menu->name = $topic->name;
                        $menu->link = 'chu-de/' . $topic->slug;
                        $menu->sort_order = $topic->sort_order; // Use existing sort_order
                        $menu->parent_id = $topic->parent_id;
                        $menu->type = 'topic';
                        $menu->position = $request->position;
                        $menu->table_id = $id;
                        $menu->created_at = date('Y-m-d H:i:s');
                        $menu->created_by = Auth::id() ?? 1;
                        $menu->status = $request->status;
                        $menu->save();
                    }
                }
                return redirect()->route('admin.menu.index');
            } else {
                return redirect()->route('admin.menu.index')->with('error', 'Không có chủ đề nào được chọn');
            }
        }

        if (isset($request->createPage)) {

            $listid = $request->pageid;

            if ($listid) {
                foreach ($listid as $id) {
                    $page = Post::where([['id', '=', $id], ['type', '=', 'page']])->first();
                    if ($page != null) {
                        $menu = new Menu();
                        $menu->name = $page->title;
                        $menu->link = 'trang-don/' . $page->slug;
                        $menu->sort_order = $page->sort_order; // Use existing sort_order
                        $menu->parent_id = $page->parent_id;
                        $menu->type = 'page';
                        $menu->position = $request->position;
                        $menu->table_id = $id;
                        $menu->created_at = date('Y-m-d H:i:s');
                        $menu->created_by = Auth::id() ?? 1;
                        $menu->status = $request->status;
                        $menu->save();
                    }
                }
                return redirect()->route('admin.menu.index');
            } else {
                return redirect()->route('admin.menu.index')->with('error', 'Không có trang đơn nào được chọn');
            }
        }

        if (isset($request->createCustom)) {
            if (isset($request->name, $request->link)) {
                $menu = new Menu();
                $menu->name = $request->name;
                $menu->link = $request->link;
                $menu->parent_id = $request->parent_id;
                $menu->sort_order = $request->sort_order;
                $menu->type = 'custom';
                $menu->position = $request->position;
                $menu->table_id = 0; // Chưa có ID liên quan, đặt mặc định là 0
                $menu->created_at = date('Y-m-d H:i:s');
                $menu->created_by = Auth::id() ?? 1;
                $menu->status = $request->status;
                $menu->save();
        
                return redirect()->route('admin.menu.index');
            } else {
                return redirect()->route('admin.menu.index')->with('error', 'Tên menu và liên kết không được để trống');
            }
        }
    }

    public function show(string $id)
    {
        $menu = Menu::find($id);
        if ($menu == null) {
            return redirect()->route('admin.menu.index');
        }
        return view("backend.menu.show", compact('menu'));
    }

    public function edit($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return redirect()->route('admin.menu.index');
        }

        $list_category = Category::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();

        $list_brand = Brand::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();

        $list_topic = Topic::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();

        $list_page = Post::where([['status', '!=', 0], ['type', '=', 'page']])
            ->select("id", "title")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.menu.edit', compact('menu', 'list_category', 'list_brand', 'list_topic', 'list_page'));
    }


    public function update(UpdateMenuRequest $request, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return redirect()->route('admin.menu.index');
        }

        $menu->name = $request->name;
        $menu->link = $request->link;
        $menu->position = $request->position;
        $menu->status = $request->status;
        $menu->updated_at = date('Y-m-d H:i:s');
        $menu->updated_by = Auth::id() ?? 1;

        $menu->save();

        return redirect()->route('admin.menu.index');
    }


    public function destroy(string $id)
    {
        $menu = Menu::find($id);
        if ($menu == null) {
            return redirect()->route('admin.menu.index');
        }
        $menu->delete();
        return redirect()->route('admin.menu.trash');
    }
    public function trash()
    {
        $list = Menu::where('status', '=', 0)
            ->select("id", "link", "name", "position", "status")
            ->orderBy('created_at', 'desc')
            ->get();
        $list_category = Category::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();
        $list_brand = Brand::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();
        $list_topic = Topic::where('status', '!=', 0)
            ->select("id", "name")
            ->orderBy('created_at', 'desc')
            ->get();
        $list_page = Post::where([['status', '!=', 0], ['type', '=', 'page']])
            ->select("id", "title")
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.menu.trash", compact('list', 'list_category', 'list_brand', 'list_topic', 'list_page'));
    }
    public function delete(string $id)
    {
        $menu = Menu::find($id);

        if ($menu == null) {
            return redirect()->route('admin.menu.index');
        }
        $menu->status = 0;
        $menu->updated_at = date('Ymd H:i:s');
        $menu->updated_by = Auth::id() ?? 1;
        $menu->save(); //Lưu

        return redirect()->route('admin.menu.index');
    }
    public function restore(string $id)
    {
        $menu = Menu::find($id);
        if ($menu == null) {
            return redirect()->route('admin.menu.index');
        }
        $menu->status = 1;
        $menu->updated_at = date('Y-m-d H:i:s');
        $menu->updated_by = Auth::id() ?? 1;
        $menu->save();
        return redirect()->route('admin.menu.trash');
    }
    public function status(string $id)
    {
        $menu = Menu::find($id);
        if ($menu == null) {
            return redirect()->route('admin.menu.index');
        }
        $menu->status = ($menu->status == 2) ? 1 : 2;
        $menu->updated_at = date('Y-m-d H:i:s');
        $menu->updated_by = Auth::id() ?? 1;
        $menu->save();
        return redirect()->route('admin.menu.index');
    }
}
