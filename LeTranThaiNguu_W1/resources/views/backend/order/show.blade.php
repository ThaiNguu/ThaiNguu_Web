@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chi tiết đơn hàng</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
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
                    $args = ['id' => $order->id];
                @endphp
                <div class="col-12 text-right">
                    <a href="{{ route('admin.order.edit', $args) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i> Sửa
                    </a>
                    <a href="{{ route('admin.order.delete', $args) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Xóa
                    </a>
                    <a class="btn btn-info btn-sm" href="{{ route('admin.order.index') }}">
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
                        <td>{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên người nhận</th>
                        <td>{{ $order->delivery_name }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ người nhận</th>
                        <td>{{ $order->delivery_address }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $order->delivery_phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $order->delivery_email }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $order->type }}</td>
                    </tr>
                    <tr>
                        <th>Note</th>
                        <td>{{ $order->note }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Ngày cập nhật</th>
                        <td>{{ $order->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>{{ $order->status }}</td>
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
