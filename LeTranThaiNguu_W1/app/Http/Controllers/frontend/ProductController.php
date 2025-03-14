<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
class ProductController extends Controller
{
    //Product all
    public function index(Request $request)
{
    $query = Product::where('status', '=', 1);

    // Lọc theo danh mục
    if ($request->has('category')) {
        $categorySlug = $request->input('category');
        $category = Category::where('slug', $categorySlug)->first();
        if ($category) {
            $listcatid = $this->getlistcategoryid($category->id);
            $query->whereIn('category_id', $listcatid);
        }
    }

    // Lọc theo thương hiệu
    if ($request->has('brand')) {
        $brandSlug = $request->input('brand');
        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $query->where('brand_id', $brand->id);
        }
    }

    // Lọc theo khoảng giá
    if ($request->has('price_min') && $request->has('price_max')) {
        $price_min = $request->input('price_min');
        $price_max = $request->input('price_max');
        $query->whereBetween('price', [$price_min, $price_max]);
    }

    // Áp dụng sắp xếp
    $query = $this->applySorting($query, $request);

    $list_product = $query->paginate(12);
    $brands = Brand::where('status', '=', 1)->get();
    $new_products = Product::where('status', '=', 1)->orderBy('created_at', 'desc')->limit(5)->get();
    $categories = Category::where('status', '=', 1)->get();

    return view("frontend.product", compact('list_product', 'brands', 'new_products', 'categories'));
}

    //get list category
    public function getlistcategoryid($rowid)
    { 
        $listcatid = [];
        
        array_push($listcatid, $rowid);
        $list1 = Category::where([['parent_id','=',$rowid],['status','=',1]])->select("id")->get();
        if (count($list1) > 0) {
            foreach ($list1 as $row1) {
                array_push($listcatid, $row1->id);
                $list2 = Category::where([['parent_id','=',$row1->id],['status','=',1]])->select("id")->get();
                if (count($list2) > 0) {
                    foreach ($list2 as $row2) {
                        array_push($listcatid, $row2->id); 
                    }
                }
            }
        }
        
        return $listcatid;
    }
    //Product category
    public function category($slug, Request $request)
    {
    $row = Category::where([['slug','=',$slug],['status','=',1]])->select("id","name","slug")->first();
    $listcatid = [];
    if ($row != null) {
        $listcatid = $this->getlistcategoryid($row->id);
    }

    $query = Product::where('status', '=', 1)
                    ->whereIn('category_id', $listcatid);

    // Xử lý sắp xếp
    if ($request->has('sort')) {
        $sort = $request->input('sort');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(pricesale, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(pricesale, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $list_product = $query->paginate(12);
    $brands = Brand::where('status', '=', 1)->get();
    $new_products = Product::where('status', '=', 1)->orderBy('created_at', 'desc')->limit(5)->get();
    $categories = Category::where('status', '=', 1)->get();

    return view("frontend.product_category", compact('list_product', 'row', 'brands', 'new_products', 'categories'));
    }   


    //Product brand
    public function brand($slug, Request $request)
    {
        $brand = Brand::where([['slug', '=', $slug], ['status', '=', 1]])->first();
        if ($brand == null) {
            return redirect()->route('site.product.index')->with('error', 'Brand not found');
        }

        $query = Product::where('status', '=', 1)
                        ->where('brand_id', $brand->id);

        // Xử lý sắp xếp
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_asc':
                    $query->orderByRaw('COALESCE(pricesale, price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(pricesale, price) DESC');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $list_product = $query->paginate(12);
        $categories = Category::where('status', '=', 1)->get();
        $brands = Brand::where('status', '=', 1)->get();
        $new_products = Product::where('status', '=', 1)->orderBy('created_at', 'desc')->limit(5)->get();

        return view("frontend.product_brand", compact('list_product', 'brand', 'categories', 'brands', 'new_products'));
    }


    //Product detail
    public function product_detail($slug)
    {
        $product = Product::where([['status','=',1],['slug','=',$slug]])->first();
        $listcatid = $this->getlistcategoryid($product->category_id);
        $list_product = Product::where([['status','=',1],['id','!=',$product->id]])
        ->whereIn('category_id',$listcatid)
        ->orderBy('created_at','desc')
        ->limit(8)
        ->get();
        return view("frontend.product_detail",compact('product','list_product'));
    }

    public function gridView(Request $request)
    {
        $query = Product::where('status', '=', 1);
    
        // Handle sorting
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_asc':
                    $query->orderByRaw('COALESCE(pricesale, price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(pricesale, price) DESC');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
    
        $list_product = $query->paginate(12);
        $categories = Category::where('status', '=', 1)->get(); // Fetch categories
        $brands = Brand::where('status', '=', 1)->get(); // Fetch brands
    
        return view('frontend.products-grid', compact('list_product', 'categories', 'brands'));
    }
    

public function listView(Request $request)
{
    $query = Product::where('status', '=', 1);

    // Xử lý sắp xếp
    if ($request->has('sort')) {
        $sort = $request->input('sort');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(pricesale, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(pricesale, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $list_product = $query->paginate(12);
        $categories = Category::where('status', '=', 1)->get(); // Fetch categories
        $brands = Brand::where('status', '=', 1)->get(); // Fetch brands
    return view('frontend.products-list', compact('list_product', 'categories', 'brands'));
    }
    public function categoryGridView($slug)
{
    $category = Category::where('slug', $slug)->firstOrFail();
    $listcatid = $this->getlistcategoryid($category->id); // Sử dụng hàm getlistcategoryid để lấy danh sách id của các danh mục con

    $query = Product::where('status', '=', 1)
                    ->whereIn('category_id', $listcatid);

    // Xử lý sắp xếp
    if (request()->has('sort')) {
        $sort = request()->input('sort');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(pricesale, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(pricesale, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $list_product = $query->paginate(12);
    $categories = Category::where('status', '=', 1)->get(); // Fetch categories
    $brands = Brand::where('status', '=', 1)->get(); // Fetch brands

    return view('frontend.category-grid', compact('category', 'list_product', 'categories', 'brands'));
}

public function categoryListView($slug)
{
    $category = Category::where('slug', $slug)->firstOrFail();
    $listcatid = $this->getlistcategoryid($category->id); // Sử dụng hàm getlistcategoryid để lấy danh sách id của các danh mục con

    $query = Product::where('status', '=', 1)
                    ->whereIn('category_id', $listcatid);

    // Xử lý sắp xếp
    if (request()->has('sort')) {
        $sort = request()->input('sort');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(pricesale, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(pricesale, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $list_product = $query->paginate(12);
    $categories = Category::where('status', '=', 1)->get(); // Fetch categories
    $brands = Brand::where('status', '=', 1)->get(); // Fetch brands

    return view('frontend.category-list', compact('category', 'list_product', 'categories', 'brands'));
}




public function brandGridView($slug, Request $request)
{
    $brand = Brand::where([['slug', '=', $slug], ['status', '=', 1]])->firstOrFail();
    $list_product = $this->applySorting($brand->products(), $request)->paginate(12);
    $brands = Brand::where('status', '=', 1)->get(); // Fetch brands
    $categories = Category::where('status', '=', 1)->get(); // Fetch categories

    return view('frontend.brand-grid', compact('list_product', 'brand', 'brands', 'categories'));
}

public function brandListView($slug, Request $request)
{
    $brand = Brand::where([['slug', '=', $slug], ['status', '=', 1]])->firstOrFail();
    $list_product = $this->applySorting($brand->products(), $request)->paginate(12);
    $brands = Brand::where('status', '=', 1)->get(); // Fetch brands
    $categories = Category::where('status', '=', 1)->get(); // Fetch categories

    return view('frontend.brand-list', compact('list_product', 'brand', 'brands', 'categories'));
}

protected function applySorting($query, Request $request)
{
    // Xử lý sắp xếp
    if ($request->has('sort')) {
        $sort = $request->input('sort');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(pricesale, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(pricesale, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    return $query;
}


}
