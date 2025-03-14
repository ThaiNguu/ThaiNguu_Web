@extends('layouts.admin')
@section('title', 'Chỉnh sửa Menu')
@section('content')
<link rel="stylesheet" href="{{ asset('bootstrap/css/menu.css') }}">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh sửa Menu</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa Menu</li>
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
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-sm btn-info">
                        <i class="fa fa-arrow-left"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.menu.update', $menu->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Tên Menu</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $menu->name }}">
                </div>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                <div class="form-group">
                    <label for="link">Liên kết</label>
                    <input type="text" name="link" id="link" class="form-control" value="{{ $menu->link }}" required>
                </div>
                <div class="form-group">
                    <label for="position">Vị trí</label>
                    <select name="position" id="position" class="form-control">
                        <option value="mainmenu" {{ $menu->position == 'mainmenu' ? 'selected' : '' }}>MainMenu</option>
                        <option value="footermenu" {{ $menu->position == 'footermenu' ? 'selected' : '' }}>FooterMenu</option>
                    </select>
                
                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" {{ $menu->status == 1 ? 'selected' : '' }}>Xuất bản</option>
                        <option value="2" {{ $menu->status == 2 ? 'selected' : '' }}>Chưa xuất bản</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Cập nhật Menu</button>
            </form>
        </div>
    </div>
</section>
@endsection
