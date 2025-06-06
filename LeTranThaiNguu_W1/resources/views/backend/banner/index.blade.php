@extends('layouts.admin')
@section('title', 'Banner')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tất cả banner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Tất cả banner</li>
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
                        <a class="btn btn-sm btn-danger" href="{{ route('admin.banner.trash') }}">
                            <i class="fa fa-trash"></i> Thùng rác
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <form action="{{ route('admin.banner.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name">Tên banner</label>
                                <input type="text" value="" name="name" id="name" class="form-control">
                            </div>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="mb-3">
                                <label for="link">Liên kết</label>
                                <input type="text" value="" name="link" id="link" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="description">Mô tả</label>
                                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="position">Vị trí</label>
                                <select name="position" id="position" class="form-control">
                                    <option value="0">None</option>
                                    {!! $htmlposition !!}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="image">Hình</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="2">Chưa xuất bản</option>
                                    <option value="1">Xuất bản</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="create" class="btn btn-success">Thêm banner</button>
                            </div>
                        </form>

                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:30px;">
                                        <input type="checkbox">
                                    </th>
                                    <th class="text-center" style="width:30px;">ID</th>
                                    <th class="text-center" style="width:100px;">Hình ảnh</th>
                                    <th class="text-center" style="width:200px;">Tên Banner</th>
                                    <th class="text-center" style="width:200px;">Link</th>
                                    <th class="text-center" style="width:200px;">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $row)
                                    <tr class="datarow">
                                        <td><input type="checkbox"></td>
                                        <td>{{ $row->id }}</td>
                                        <td class="text-center"><img src="{{ asset('images/banners/'. $row->image )}}" alt="{{ $row->image }}" style="width: 100px;"></td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->link }}</td>

                                        @php
                                            $args = ['id' => $row->id];
                                        @endphp
                                         <td class="text-center">
                                            @if ($row->status == 1)
                                            <a href="{{ route('admin.banner.status',$args) }}" class="btn btn-success" style="width:40px;"><i class="fas fa-toggle-on"></i></a>
                                        @else
                                            <a href="{{ route('admin.banner.status',$args) }}" class="btn btn-danger" style="width:40px;"><i class="fas fa-toggle-off"></i></a>
                                        @endif
                                                <a href="{{ route('admin.banner.edit',$args) }}" class="btn btn-primary" style="width:40px;"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.banner.show',$args) }}" class="btn btn-info" style="width:40px;"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.banner.delete',$args) }}" class="btn btn-danger" style="width:40px;"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
