@extends('layouts.admin')
@section('title', 'Chi tiết thành viên')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chi tiết thành viên</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Chi tiết thành viên</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="card">
        <div class="card-header py-2">
            <div class="row">
                @php
                    $args = ['id' => $user->id];
                @endphp
                <div class="col-12 text-right">
                    <a href="{{ route('admin.user.edit', $args) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i> Sửa
                    </a>
                    <a href="{{ route('admin.user.delete', $args) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Xóa
                    </a>
                    <a class="btn btn-info btn-sm" href="{{ route('admin.user.index') }}">
                        <i class="fa fa-arrow-left"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr class="table-header text-center">
                        <th class="text-bold text-large">Tên trường</th>
                        <td class="text-bold text-large">Giá trị</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Hình ảnh</th>
                        <td><img src="{{ asset('images/users/'.$user->image) }}" class="img-fluid" alt="{{ $user->image }}" style="max-width: 200px;"></td>
                    </tr>
                    <tr>
                        <th>Họ tên</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Điện thoại</th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Giới tính</th>
                        <td>{{ $user->gender == 1 ? 'Nam' : 'Nữ' }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>{{ $user->address }}</td>
                    </tr>
                    <tr>
                        <th>Tên đăng nhập</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Mật khẩu</th>
                        <td>{{ $user->password }}</td>
                    </tr>
                    <tr>
                        <th>Quyền</th>
                        <td>{{ $user->roles }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>{{ $user->status == 1 ? 'Xuất bản' : 'Chưa xuất bản' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Ngày cập nhật</th>
                        <td>{{ $user->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>Người tạo</th>
                        <td>{{ $user->created_by }}</td>
                    </tr>
                    <tr>
                        <th>Người cập nhật</th>
                        <td>{{ $user->updated_by }}</td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .table-header th, .table-header td {
        font-size: 1.25rem; /* Larger font size */
        font-weight: bold; /* Bold text */
    }
    .text-large {
        font-size: 1.25rem; /* Larger font size for value cells */
    }
    .text-bold {
        font-weight: bold; /* Bold text for value cells */
    }
    .card-header {
        padding: 0.5rem 1rem; /* Smaller padding */
    }
    .btn-sm {
        padding: 0.25rem 0.5rem; /* Smaller padding for buttons */
        font-size: 0.875rem; /* Smaller font size */
    }
</style>
@endsection
