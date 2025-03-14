@extends('layouts.site')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Đăng ký thành viên</h2>
    <form action="{{ route('frontend.user.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Họ và tên *</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nhập họ và tên...">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại *</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại...">
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập địa chỉ email...">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="username">Tên đăng nhập *</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Chọn tên đăng nhập...">
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu *</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu...">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Xác nhận mật khẩu *</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu...">
        </div>
        <div class="form-group">
            <label for="image">Hình đại diện</label>
            <input type="file" class="form-control-file" id="image" name="image">
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
    </form>
</div>
@endsection

@section('title', 'Đăng ký')
