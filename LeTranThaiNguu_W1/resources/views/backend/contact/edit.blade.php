@extends('layouts.admin')
@section('title', 'Chỉnh sửa liên hệ')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh sửa liên hệ</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa liên hệ</li>
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
                    <a class="btn btn-sm btn-info" href="{{ route('admin.contact.index') }}">
                        <i class="fa fa-arrow-left"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.contact.update', $contact->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Tên</label>
                    <input type="text" name="name" value="{{ $contact->name }}" class="form-control" id="name" placeholder="Nhập tên">
                </div>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ $contact->email }}" class="form-control" id="email" placeholder="Nhập email">
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ $contact->phone }}" class="form-control" id="phone" placeholder="Nhập số điện thoại">
                </div>
                <div class="form-group">
                    <label for="title">Tiêu đề</label>
                    <input type="text" name="title" value="{{ $contact->title }}" class="form-control" id="title" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group">
                    <label for="content">Nội dung</label>
                    <textarea name="content" class="form-control" id="content" placeholder="Nhập nội dung">{{ $contact->content }}</textarea>
                </div>
                <div class="form-group">
                    <label for="replay_id">ID phản hồi</label>
                    <input type="text" name="replay_id" value="{{ $contact->replay_id }}" class="form-control" id="replay_id" placeholder="Nhập ID phản hồi">
                </div>
                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select name="status" class="form-control" id="status">
                        <option value="1" {{ $contact->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="2" {{ $contact->status == 2 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</section>
@endsection
