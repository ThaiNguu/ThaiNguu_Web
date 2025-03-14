@extends('layouts.admin')
@section('title', 'Thùng rác Menu')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Thùng rác Menu</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Thùng rác Menu</li>
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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width:30px;">ID</th>
                        <th class="text-center" style="width:200px;">Tên menu</th>
                        <th class="text-center" style="width:200px;">Link</th>
                        <th class="text-center" style="width:200px;">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $row)
                        <tr class="datarow">
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->link }}</td>
                            @php
                            $args = ['id' => $row->id];
                        @endphp
                        <td class="text-center">
                            <a href="{{ route('admin.menu.restore', $args) }}" class="btn btn-primary" style="width:40px; height: 40px;"><i class="fas fa-undo"></i></a>
                            <a href="{{ route('admin.menu.show', $args) }}" class="btn btn-info" style="width:40px; height: 40px;"><i class="fas fa-eye"></i></a>
                            <form class="d-inline" action="{{ route('admin.menu.destroy', $args) }}" method="post">
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
