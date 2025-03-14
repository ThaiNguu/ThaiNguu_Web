@extends('layouts.admin')
@section('title','Sửa chủ đề')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cập nhật chủ đề</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.topic.index') }}">Danh sách chủ đề</a></li>
                        <li class="breadcrumb-item active">Cập nhật chủ đề</li>
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
                        <a class="btn btn-sm btn-info" href="{{ route('admin.topic.index') }}">
                            <i class="fa fa-arrow-left"></i> Về danh sách
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.topic.update', ['id' => $topic->id]) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="name">Tên chủ đề</label>
                        <input type="text" value="{{ old('name', $topic->name) }}" name="name" id="name" class="form-control">
                    </div>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="mb-3">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $topic->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sort_order">Sắp xếp</label>
                        <select name="sort_order" id="sort_order" class="form-control">
                            <option value="0" {{ ($topic->sort_order == 0) ? 'selected' : '' }}>None</option>
                            {!! $htmlsortorder !!}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="2" {{ ($topic->status == 2) ? 'selected' : '' }}>Chưa xuất bản</option>
                            <option value="1" {{ ($topic->status == 1) ? 'selected' : '' }}>Xuất bản</option>
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
