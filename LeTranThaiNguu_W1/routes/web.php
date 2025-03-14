<?php

require __DIR__.'/api.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\ProductSearchControllerNew;
use App\Http\Controllers\frontend\ProductController as SanphamController;
use App\Http\Controllers\frontend\FrontendUserController;
use App\Http\Controllers\frontend\ContactController as LienheController;
use App\Http\Controllers\frontend\PostController as BaiVietController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\PageController;

//using controller admin
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\BannerController;
use App\Http\Controllers\backend\BrandController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\ContactController;
use App\Http\Controllers\backend\MenuController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\PostController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\TopicController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\MiddleAuth;
//route site
// Frontend User Routes

//Home

Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/trang-don', [PageController::class, 'index'])->name('site.page');
Route::get('/trang-don/{slug}', [PageController::class, 'show'])->name('frontend.page.show');

//Product all
Route::get('/san-pham', [SanphamController::class, 'index'])->name('site.product.index');
Route::get('/san-pham/grid', [SanphamController::class, 'gridView'])->name('site.products.grid');
Route::get('/san-pham/list', [SanphamController::class, 'listView'])->name('site.products.list');
//Product category
Route::get('/danh-muc/{slug}', [SanphamController::class, 'category'])->name('site.product.category');

Route::get('/danh-muc/{slug}/grid', [SanphamController::class, 'categoryGridView'])->name('site.product.category.grid');
Route::get('/danh-muc/{slug}/list', [SanphamController::class, 'categoryListView'])->name('site.product.category.list');

// Routes for brands
Route::get('/thuong-hieu/{slug}/grid', [SanphamController::class, 'brandGridView'])->name('site.product.brand.grid');
Route::get('/thuong-hieu/{slug}/list', [SanphamController::class, 'brandListView'])->name('site.product.brand.list');

//Product brand
Route::get('/thuong-hieu/{slug}', [App\Http\Controllers\frontend\ProductController::class, 'brand'])->name('site.product.brand');
//Post all
Route::get('/bai-viet', [BaiVietController::class, 'index'])->name('site.post');
Route::get('/bai-viet/chu-de/{slug}', [BaiVietController::class, 'topic'])->name('site.post.topic');
Route::get('/bai-viet/{slug}', [BaiVietController::class, 'post_detail'])->name('site.post.detail');
//Login
Route::get("dang-nhap",[AuthController::class,"getlogin"])->name('website.getlogin');
Route::post("dang-nhap",[AuthController::class,"dologin"])->name('website.dologin');
Route::get("dang-xuat",[AuthController::class,"logout"])->name('website.logout');
//Product detail
Route::get('/chi-tiet-san-pham/{slug}', [SanphamController::class, 'product_detail'])->name('site.product.detail');
//Contact
Route::get('/lien-he', [LienheController::class, 'index'])->name('contact.index');
Route::post('/lien-he', [LienheController::class, 'store'])->name('contact.store');
//Search
Route::get('/search', [ProductSearchControllerNew::class, 'search'])->name('search');
//giỏ hàng
Route::get('gio-hang', [CartController::class, 'index'])->name('site.cart.index');
Route::get('/addcart', [CartController::class, 'addcart'])->name('site.addcart');
Route::post('cart/update', [CartController::class, 'update'])->name('site.cart.update');
Route::get('cart/delete/{id}', [CartController::class, 'delete'])->name('site.cart.delete');
Route::get('thanh-toan', [CartController::class, 'checkout'])->name('site.cart.checkout');
Route::post('thanh-toan', [CartController::class, 'docheckout'])->name('site.cart.docheckout');

//route admin -- Kiểm tra đăng nhập bằng middleware
Route::prefix('admin')->middleware("middleauth")->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix("product")->group(function () {
        Route::get("/", [ProductController::class, 'index'])->name('admin.product.index');
        Route::get("trash", [ProductController::class, 'trash'])->name('admin.product.trash');
        Route::get("show/{id}", [ProductController::class, 'show'])->name('admin.product.show');
        Route::get("create", [ProductController::class, 'create'])->name('admin.product.create');
        Route::post("store", [ProductController::class, 'store'])->name('admin.product.store');
        Route::get("edit/{id}", [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::put("update/{id}", [ProductController::class, 'update'])->name('admin.product.update');
        Route::get("status/{id}", [ProductController::class, 'status'])->name('admin.product.status');
        Route::get("delete/{id}", [ProductController::class, 'delete'])->name('admin.product.delete');
        Route::get("restore/{id}", [ProductController::class, 'restore'])->name('admin.product.restore');
        Route::delete("destroy/{id}", [ProductController::class, 'destroy'])->name('admin.product.destroy');
    });

    Route::prefix("banner")->group(function () {
        Route::get("/", [BannerController::class, 'index'])->name('admin.banner.index');
        Route::get("trash", [BannerController::class, 'trash'])->name('admin.banner.trash');
        Route::get("show/{id}", [BannerController::class, 'show'])->name('admin.banner.show');
        Route::get("create", [BannerController::class, 'create'])->name('admin.banner.create');
        Route::post("store", [BannerController::class, 'store'])->name('admin.banner.store');
        Route::get("edit/{id}", [BannerController::class, 'edit'])->name('admin.banner.edit');
        Route::put("update/{id}", [BannerController::class, 'update'])->name('admin.banner.update');
        Route::get("status/{id}", [BannerController::class, 'status'])->name('admin.banner.status');
        Route::get("delete/{id}", [BannerController::class, 'delete'])->name('admin.banner.delete');
        Route::get("restore/{id}", [BannerController::class, 'restore'])->name('admin.banner.restore');
        Route::delete("destroy/{id}", [BannerController::class, 'destroy'])->name('admin.banner.destroy');
    });

    Route::prefix("brand")->group(function () {
        Route::get("/", [BrandController::class, 'index'])->name('admin.brand.index');
        Route::get("trash", [BrandController::class, 'trash'])->name('admin.brand.trash');
        Route::get("show/{id}", [BrandController::class, 'show'])->name('admin.brand.show');
        Route::post("store", [BrandController::class, 'store'])->name('admin.brand.store');
        Route::get("edit/{id}", [BrandController::class, 'edit'])->name('admin.brand.edit');
        Route::put("update/{id}", [BrandController::class, 'update'])->name('admin.brand.update');
        Route::get("status/{id}", [BrandController::class, 'status'])->name('admin.brand.status');
        Route::get("delete/{id}", [BrandController::class, 'delete'])->name('admin.brand.delete');
        Route::get("restore/{id}", [BrandController::class, 'restore'])->name('admin.brand.restore');
        Route::delete("destroy/{id}", [BrandController::class, 'destroy'])->name('admin.brand.destroy');
    });

    Route::prefix("category")->group(function () {
        Route::get("/", [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get("trash", [CategoryController::class, 'trash'])->name('admin.category.trash');
        Route::get("show/{id}", [CategoryController::class, 'show'])->name('admin.category.show');
        Route::post("store", [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get("edit/{id}", [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::put("update/{id}", [CategoryController::class, 'update'])->name('admin.category.update');
        Route::get("status/{id}", [CategoryController::class, 'status'])->name('admin.category.status');
        Route::get("delete/{id}", [CategoryController::class, 'delete'])->name('admin.category.delete');
        Route::get("restore/{id}", [CategoryController::class, 'restore'])->name('admin.category.restore');
        Route::delete("destroy/{id}", [CategoryController::class, 'destroy'])->name('admin.category.destroy');
    });

    Route::prefix("contact")->group(function () {
        Route::get("/", [ContactController::class, 'index'])->name('admin.contact.index');
        Route::get("trash", [ContactController::class, 'trash'])->name('admin.contact.trash');
        Route::get("show/{id}", [ContactController::class, 'show'])->name('admin.contact.show');
        Route::post("store", [ContactController::class, 'store'])->name('admin.contact.store');
        Route::get("edit/{id}", [ContactController::class, 'edit'])->name('admin.contact.edit');
        Route::put("update/{id}", [ContactController::class, 'update'])->name('admin.contact.update');
        Route::get("status/{id}", [ContactController::class, 'status'])->name('admin.contact.status');
        Route::get("delete/{id}", [ContactController::class, 'delete'])->name('admin.contact.delete');
        Route::get("restore/{id}", [ContactController::class, 'restore'])->name('admin.contact.restore');
        Route::delete("destroy/{id}", [ContactController::class, 'destroy'])->name('admin.contact.destroy');
    });

    Route::prefix("order")->group(function () {
        Route::get("/", [OrderController::class, 'index'])->name('admin.order.index');
        Route::get("trash", [OrderController::class, 'trash'])->name('admin.order.trash');
        Route::get("show/{id}", [OrderController::class, 'show'])->name('admin.order.show');
        Route::post("store", [OrderController::class, 'store'])->name('admin.order.store');
        Route::get("edit/{id}", [OrderController::class, 'edit'])->name('admin.order.edit');
        Route::put("update/{id}", [OrderController::class, 'update'])->name('admin.order.update');
        Route::get("status/{id}", [OrderController::class, 'status'])->name('admin.order.status');
        Route::get("delete/{id}", [OrderController::class, 'delete'])->name('admin.order.delete');
        Route::get("restore/{id}", [OrderController::class, 'restore'])->name('admin.order.restore');
        Route::delete("destroy/{id}", [OrderController::class, 'destroy'])->name('admin.order.destroy');
    });

    Route::prefix("post")->group(function () {
        Route::get("/", [PostController::class, 'index'])->name('admin.post.index');
        Route::get("trash", [PostController::class, 'trash'])->name('admin.post.trash');
        Route::get("show/{id}", [PostController::class, 'show'])->name('admin.post.show');
        Route::get("create", [PostController::class, 'create'])->name('admin.post.create');
        Route::post("store", [PostController::class, 'store'])->name('admin.post.store');
        Route::get("edit/{id}", [PostController::class, 'edit'])->name('admin.post.edit');
        Route::put("update/{id}", [PostController::class, 'update'])->name('admin.post.update');
        Route::get("status/{id}", [PostController::class, 'status'])->name('admin.post.status');
        Route::get("delete/{id}", [PostController::class, 'delete'])->name('admin.post.delete');
        Route::get("restore/{id}", [PostController::class, 'restore'])->name('admin.post.restore');
        Route::delete("destroy/{id}", [PostController::class, 'destroy'])->name('admin.post.destroy');
    });

    Route::prefix("menu")->group(function () {
        Route::get("/", [MenuController::class, 'index'])->name('admin.menu.index');
        Route::get("trash", [MenuController::class, 'trash'])->name('admin.menu.trash');
        Route::get("show/{id}", [MenuController::class, 'show'])->name('admin.menu.show');
        Route::get("create", [MenuController::class, 'create'])->name('admin.menu.create');
        Route::post("store", [MenuController::class, 'store'])->name('admin.menu.store');
        Route::get("edit/{id}", [MenuController::class, 'edit'])->name('admin.menu.edit');
        Route::put("update/{id}", [MenuController::class, 'update'])->name('admin.menu.update');
        Route::get("status/{id}", [MenuController::class, 'status'])->name('admin.menu.status');
        Route::get("delete/{id}", [MenuController::class, 'delete'])->name('admin.menu.delete');
        Route::get("restore/{id}", [MenuController::class, 'restore'])->name('admin.menu.restore');
        Route::delete("destroy/{id}", [MenuController::class, 'destroy'])->name('admin.menu.destroy');
    });

    Route::prefix("topic")->group(function () {
        Route::get("/", [TopicController::class, 'index'])->name('admin.topic.index');
        Route::get("trash", [TopicController::class, 'trash'])->name('admin.topic.trash');
        Route::get("show/{id}", [TopicController::class, 'show'])->name('admin.topic.show');
        Route::post("store", [TopicController::class, 'store'])->name('admin.topic.store');
        Route::get("edit/{id}", [TopicController::class, 'edit'])->name('admin.topic.edit');
        Route::put("update/{id}", [TopicController::class, 'update'])->name('admin.topic.update');
        Route::get("status/{id}", [TopicController::class, 'status'])->name('admin.topic.status');
        Route::get("delete/{id}", [TopicController::class, 'delete'])->name('admin.topic.delete');
        Route::get("restore/{id}", [TopicController::class, 'restore'])->name('admin.topic.restore');
        Route::delete("destroy/{id}", [TopicController::class, 'destroy'])->name('admin.topic.destroy');
    });

    Route::prefix("user")->group(function () {
        Route::get("/", [UserController::class, 'index'])->name('admin.user.index');
        Route::get("trash", [UserController::class, 'trash'])->name('admin.user.trash');
        Route::get("show/{id}", [UserController::class, 'show'])->name('admin.user.show');
        Route::get("create", [UserController::class, 'create'])->name('admin.user.create');
        Route::post("store", [UserController::class, 'store'])->name('admin.user.store');
        Route::get("edit/{id}", [UserController::class, 'edit'])->name('admin.user.edit');
        Route::put("update/{id}", [UserController::class, 'update'])->name('admin.user.update');
        Route::get("status/{id}", [UserController::class, 'status'])->name('admin.user.status');
        Route::get("delete/{id}", [UserController::class, 'delete'])->name('admin.user.delete');
        Route::get("restore/{id}", [UserController::class, 'restore'])->name('admin.user.restore');
        Route::delete("destroy/{id}", [UserController::class, 'destroy'])->name('admin.user.destroy');
    });
});
