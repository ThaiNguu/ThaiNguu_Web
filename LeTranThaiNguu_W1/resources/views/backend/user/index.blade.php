@extends('layouts.admin')
@section('title', 'Thành viên')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tất cả thành viên</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Tất cả thành viên</li>
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
                        <a href="{{ route('admin.user.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Thêm thành viên</a>
                        <a class="btn btn-sm btn-danger" href="{{ route('admin.user.trash') }}">
                            <i class="fa fa-trash"></i> Thùng rác
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="mytable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:30px;"><input type="checkbox"></th>
                            <th class="text-center" style="width:30px;">ID</th>
                            <th class="text-center" style="width:100px;">Hình ảnh</th>
                            <th class="text-center" style="width:130px;">Họ tên</th>
                            <th class="text-center" style="width:300px;">Điện thoại</th>
                            <th class="text-center" style="width:200px;">Email</th>
                            <th class="text-center" style="width:250px;">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $row)
                        <tr class="datarow" >
                            <td><input type="checkbox"></td>
                            <td>{{ $row->id }}</td>
                            <td class="text-center"><img src="{{ asset('images/users/'. $row->image )}}" alt="{{ $row->image }}" style="width: 100px;"></td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->email }}</td>
                            @php
                                $args = ['id'=>$row->id];    
                            @endphp
                             <td class="text-center">
                                @if ($row->status == 1)
                                <a href="{{ route('admin.user.status',$args) }}" class="btn btn-success" style="width:40px;"><i class="fas fa-toggle-on"></i></a>
                            @else
                                <a href="{{ route('admin.user.status',$args) }}" class="btn btn-danger" style="width:40px;"><i class="fas fa-toggle-off"></i></a>
                            @endif
                                    <a href="{{ route('admin.user.edit',$args) }}" class="btn btn-primary" style="width:40px;"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.user.show',$args) }}" class="btn btn-info" style="width:40px;"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.user.delete',$args) }}" class="btn btn-danger" style="width:40px;"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>


@endsection
