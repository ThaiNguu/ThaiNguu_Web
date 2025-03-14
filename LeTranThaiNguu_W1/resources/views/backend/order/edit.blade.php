@extends('layouts.admin')
@section('title','Sửa đơn hàng')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Cập nhật đơn hàng</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Cập nhật đơn hàng</li>
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
                    <a class="btn btn-sm btn-info" href="{{ route('admin.order.index') }}">
                        <i class="fa fa-arrow-left"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.order.update', ['id' => $order->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="delivery_name">Tên người nhận</label>
                    <input type="text" value="{{ old('delivery_name', $order->delivery_name) }}" name="delivery_name" id="delivery_name" class="form-control">
                </div>
                @error('delivery_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mb-3">
                    <label for="delivery_address">Địa chỉ người nhận</label>
                    <input type="text" value="{{ old('delivery_address', $order->delivery_address) }}" name="delivery_address" id="delivery_address" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="delivery_phone">Số điện thoại</label>
                    <input type="text" value="{{ old('delivery_phone', $order->delivery_phone) }}" name="delivery_phone" id="delivery_phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="delivery_email">Email</label>
                    <input type="email" value="{{ old('delivery_email', $order->delivery_email) }}" name="delivery_email" id="delivery_email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="type">Type</label>
                    <input type="text" value="{{ old('type', $order->type) }}" name="type" id="type" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="type">Note</label>
                    <input type="text" value="{{ old('note', $order->note) }}" name="note" id="note" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="status">Trạng thái</label>
                    <select name="status" id="status" class="form-control">
                        <option value="2" {{ ($order->status == 2) ? 'selected' : '' }}>Chưa xử lý</option>
                        <option value="1" {{ ($order->status == 1) ? 'selected' : '' }}>Đã xử lý</option>
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
