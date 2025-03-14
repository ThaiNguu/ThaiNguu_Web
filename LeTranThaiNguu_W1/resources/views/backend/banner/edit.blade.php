@extends('layouts.admin')
@section('title','Sửa banner')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cập nhật banner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Cập nhật banner</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 text-right">
                        <a class="btn btn-sm btn-info" href="{{ route('admin.banner.index') }}">
                            <i class="fa fa-arrow-left"></i> Về danh sách
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banner.update', ['id' => $banner->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="name">Tên banner</label>
                        <input type="text" value="{{ old('name', $banner->name) }}" name="name" id="name" class="form-control">
                    </div>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="mb-3">
                        <label for="link">Liên kết</label>
                        <input type="text" value="{{ old('link', $banner->link) }}" name="link" id="link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $banner->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="position">Vị trí</label>
                        <select name="position" id="position" class="form-control">
                            <option value="0" {{ ($banner->position == 0) ? 'selected' : '' }}>None</option>
                            {!! $htmlposition !!}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image">Hình</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if ($banner->image)
                            <img src="{{ asset('images/banners/' . $banner->image) }}" alt="{{ $banner->name }}" class="img-thumbnail mt-2" width="150">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="2" {{ ($banner->status == 2) ? 'selected' : '' }}>Chưa xuất bản</option>
                            <option value="1" {{ ($banner->status == 1) ? 'selected' : '' }}>Xuất bản</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="update" class="btn btn-success">Xác nhận sửa</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
