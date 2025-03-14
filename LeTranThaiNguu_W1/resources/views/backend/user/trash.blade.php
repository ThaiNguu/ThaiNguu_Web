@extends('layouts.admin')
@section('title', 'Thùng rác thành viên')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Thùng rác thành viên</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Thùng rác thành viên</li>
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
                    <a class="btn btn-sm btn-info" href="{{ route('admin.user.index') }}">
                        <i class="fa fa-arrow-left"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width:30px;">
                            <input type="checkbox">
                        </th>
                        <th class="text-center" style="width:30px;">ID</th>
                        <th class="text-center" style="width:100px;">Hình ảnh</th>
                        <th class="text-center" style="width:200px;">Tên thành viên</th>
                        <th class="text-center" style="width:200px;">Email</th>
                        <th class="text-center" style="width:200px;">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $row)
                    <tr class="datarow">
                        <td><input type="checkbox"></td>
                        <td>{{ $row->id }}</td>
                        <td><img src="{{ asset('images/users/' . $row->image) }}" alt="{{ $row->image }}" style="width: 100px;"></td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        @php
                            $args = ['id' => $row->id];
                        @endphp
                        <td class="text-center">
                            <a href="{{ route('admin.user.restore', $args) }}" class="btn btn-primary" style="width:40px; height: 40px;"><i class="fas fa-undo"></i></a>
                            <a href="{{ route('admin.user.show', $args) }}" class="btn btn-info" style="width:40px; height: 40px;"><i class="fas fa-eye"></i></a>
                            <form class="d-inline" action="{{ route('admin.user.destroy', $args) }}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger" type="submit" style="width:40px; height: 40px;"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
