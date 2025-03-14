<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\TopicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\SearchController;

Route::apiResource('product', 'App\Http\Controllers\API\ProductController');
Route::apiResource('banner', 'App\Http\Controllers\API\BannerController');
Route::apiResource('brand', 'App\Http\Controllers\API\BrandController');
Route::apiResource('category', 'App\Http\Controllers\API\CategoryController');
Route::apiResource('post', 'App\Http\Controllers\API\PostController');
Route::apiResource('topic', 'App\Http\Controllers\API\TopicController');
Route::apiResource('order', 'App\Http\Controllers\API\OrderController');
Route::apiResource('orderdetail', 'App\Http\Controllers\API\OrderDetailController');
Route::apiResource('contact', 'App\Http\Controllers\API\ContactController');
Route::apiResource('user', 'App\Http\Controllers\API\UserController');
Route::apiResource('payment', 'App\Http\Controllers\API\PaymentController');
Route::put('/product/{id}', [ProductController::class, 'update']);

Route::apiResource('users', UserController::class);
// Trong api.php
Route::get('/posts/by-topic', [PostController::class, 'getPostsByTopic']);

//Upload image
Route::post('product/{id}/upload-image', [ProductController::class, 'uploadImage']);
Route::post('brand/{id}/upload-image', [BrandController::class, 'uploadImage']);
Route::post('category/{id}/upload-image', [CategoryController::class, 'uploadImage']);
Route::post('post/{id}/upload-image', [PostController::class, 'uploadImage']);
Route::post('banner/{id}/upload-image', [BannerController::class, 'uploadImage']);
Route::post('users/{id}/upload-image', [UserController::class, 'uploadImage']);
//Status
Route::patch('product/status/{id}', [ProductController::class, 'status']);
Route::patch('category/status/{id}', [CategoryController::class, 'status']);
Route::patch('brand/status/{id}', [BrandController::class, 'status']);
Route::patch('user/status/{id}', [UserController::class, 'status']);
Route::patch('banner/status/{id}', [BannerController::class, 'status']);
Route::patch('post/status/{id}', [PostController::class, 'status']);
Route::patch('order/status/{id}', [OrderController::class, 'status']);
Route::patch('contact/status/{id}', [ContactController::class, 'status']);
Route::patch('topic/status/{id}', [TopicController::class, 'status']);
//Delete
Route::patch('banner/delete/{id}', [BannerController::class, 'delete']);
Route::patch('product/delete/{id}', [ProductController::class, 'delete']);
Route::patch('category/delete/{id}', [CategoryController::class, 'delete']);
Route::patch('brand/delete/{id}', [BrandController::class, 'delete']);
Route::patch('user/delete/{id}', [UserController::class, 'delete']);
Route::patch('post/delete/{id}', [PostController::class, 'delete']);
Route::patch('order/delete/{id}', [OrderController::class, 'delete']);
Route::patch('contact/delete/{id}', [ContactController::class, 'delete']);
Route::patch('topic/delete/{id}', [TopicController::class, 'delete']);

Route::get('categories/{slug}/products', [CategoryController::class, 'getProductsByCategory']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::middleware(['auth:api'])->get('user', [AuthController::class, 'getUser']);
Route::post('/payment', [PaymentController::class, 'store']);
Route::get('/products/search', [SearchController::class, 'search']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
