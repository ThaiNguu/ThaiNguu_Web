@extends('layouts.site')
@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Thông Tin Liên Hệ</h2>
        <p>Gọi Điện : 0376548379</p>
        <p>Email : thainguu.com.vn</p>
        <p>Giờ Làm Việc : 07:30 - 22:30 Các Ngày Trong Tuần (Kể Cả Ngày Lễ)</p>
        <h2 class="text-center">Phản Ánh – Khiếu Nại</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Họ và tên *</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập họ và tên...">
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Nhập địa chỉ email...">
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại *</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại...">
            </div>
            <div class="form-group">
                <label for="title">Tiêu đề *</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề...">
            </div>
            
            <div class="form-group">
                <label for="content">Chi tiết phản ánh *</label>
                <textarea class="form-control" id="content" name="content" rows="3" placeholder="Vui lòng nhập nội dung..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-secondary btn-block">Gửi phản ánh</button>
        </form>
    </div>
@endsection
