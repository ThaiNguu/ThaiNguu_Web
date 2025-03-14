@extends('layouts.admin')
@section('title','Sửa sản phẩm')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cập nhật sản phẩm</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Cập nhật sản phẩm</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card"> 
            <div class="card-body">
                <form action="{{ route('admin.product.update',['id'=>$product->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button type="submit" name="create" class="btn btn-success mb-3">Xác nhận sửa</button>
                                    <a class="btn btn-info mb-3" href="{{ route('admin.product.index') }}">
                                        <i class="fa fa-arrow-left"></i> Về danh sách
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" value="{{ old('name', $product->name) }}" name="name" id="name" class="form-control">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="detail">Chi tiết</label>
                                        <textarea name="detail" id="detail" rows="8" class="form-control">{{ old('detail', $product->detail) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="category_id">Danh mục</label>
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="brand_id">Thương hiệu</label>
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            <option value="">Chọn thương hiệu</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Giá</label>
                                        <input type="number" value="{{ old('price', $product->price) }}" name="price" id="price" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="pricesale">Giá khuyến mãi</label>
                                        <input type="number" value="{{ old('pricesale', $product->pricesale) }}" name="pricesale" id="pricesale" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Hình</label>
                                        <input type="file" name="image" id="image" class="form-control">
                                        @if ($product->image)
                                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail mt-2" width="150">
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Trạng thái</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="2" {{ $product->status == 2 ? 'selected' : '' }}>Chưa xuất bản</option>
                                            <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Xuất bản</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
