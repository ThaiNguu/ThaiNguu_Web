@extends('layouts.admin')
@section('title', 'Chi tiết Menu')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chi tiết Menu</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Chi tiết Menu</li>
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
                    $args = ['id' => $menu->id];
                @endphp
                <div class="col-12 text-right">
                    <a href="{{ route('admin.menu.edit', $args) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i> Sửa
                    </a>
                    <a href="{{ route('admin.menu.delete', $args) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Xóa
                    </a>
                    <a class="btn btn-info btn-sm" href="{{ route('admin.menu.index') }}">
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
                        <th>ID</th>
                        <td>{{ $menu->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên Menu</th>
                        <td>{{ $menu->name }}</td>
                    </tr>
                    <tr>
                        <th>Link</th>
                        <td>{{ $menu->link }}</td>
                    </tr>
                    <tr>
                        <th>Vị trí</th>
                        <td>{{ $menu->position }}</td>
                    </tr>
                    <tr>
                        <th>Loại</th>
                        <td>{{ $menu->type }}</td>
                    </tr>
                    <tr>
                        <th>Sắp xếp</th>
                        <td>{{ $menu->sort_order }}</td>
                    </tr>
                    <tr>
                        <th>Cấp cha</th>
                        <td>{{ $menu->parent_id }}</td>
                    </tr>   
                    <tr>
                        <th>Bảng</th>
                        <td>{{ $menu->table_id }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $menu->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Người tạo</th>
                        <td>{{ $menu->created_by }}</td>
                    </tr>
                    <tr>
                        <th>Ngày cập nhật</th>
                        <td>{{ $menu->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>Người cập nhật</th>
                        <td>{{ $menu->updated_by }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>{{ $menu->status == 1 ? 'Hiển thị' : 'Ẩn' }}</td>
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
