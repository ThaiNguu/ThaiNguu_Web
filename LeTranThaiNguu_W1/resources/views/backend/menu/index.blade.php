@extends('layouts.admin')
@section('title', 'Menu')
@section('content')
<link rel="stylesheet" href="{{ asset('bootstrap/css/menu.css') }}">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tất cả menu</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ asset('admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Tất cả menu</li>
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
                        <a class="btn btn-sm btn-danger" href="{{ route('admin.menu.trash') }}">
                            <i class="fa fa-trash"></i> Thùng rác
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <form action="{{ route('admin.menu.store') }}" method="post">
                            @csrf
                            <div class="accordion" id="accordionExample">
                                <div class="form-group">
                                    <label for="position">Vị trí</label>
                                    <select name="position" id="position" class="form-control">
                                        <option value="mainmenu">MainMenu</option>
                                        <option value="footermenu">FooterMenu</option>
                                        <!-- Các lựa chọn khác -->
                                    </select>
                                </div>

                                <div class="card">
                                    <div class="card-header" id="headingCategory">
                                        <a class="d-block" data-toggle="collapse"
                                            data-target="#collapseCategory" aria-expanded="true"
                                            aria-controls="collapseCategory">
                                            <label class="text-dark">Danh mục</label>
                                        </a>
                                    </div>
                                    <div id="collapseCategory" class="collapse"
                                        aria-labelledby="headingCategory" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @foreach ($list_category as $category)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" name="categoryid[]" type="checkbox" value="{{ $category->id }}" id="category{{ $category->id }}">
                                                <label class="form-check-label" for="category{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                            
                                            <div class="mb-3">
                                                <input type="submit" name="createCategory" class="btn btn-success" value="Thêm Menu">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                                <div class="card">
                                    <div class="card-header" id="headingBrand">
                                        <a class="d-block" data-toggle="collapse"
                                            data-target="#collapseBrand" aria-expanded="true"
                                            aria-controls="collapseBrand">
                                            <label class="text-dark">Thương hiệu</label>
                                        </a>
                                    </div>
                                    <div id="collapseBrand" class="collapse"
                                        aria-labelledby="headingBrand" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @foreach ($list_brand as $brand)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" name="brandid[]" type="checkbox" value="{{ $brand->id }}" id="brand{{ $brand->id }}">
                                                <label class="form-check-label" for="brand{{ $brand->id }}">
                                                    {{ $brand->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                            <div class="mb-3">
                                                <input type="submit" name="createBrand" class="btn btn-success" value="Thêm Menu">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                                <div class="card">
                                    <div class="card-header" id="headingTopic">
                                        <a class="d-block" data-toggle="collapse"
                                            data-target="#collapseTopic" aria-expanded="true"
                                            aria-controls="collapseTopic">
                                            <label class="text-dark">Chủ đề</label>
                                        </a>
                                    </div>
                                    <div id="collapseTopic" class="collapse"
                                        aria-labelledby="headingTopic" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @foreach ($list_topic as $topic)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" name="topicid[]" type="checkbox" value="{{ $topic->id }}" id="topic{{ $topic->id }}">
                                                <label class="form-check-label" for="topic{{ $topic->id }}">
                                                    {{ $topic->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                            <div class="mb-3">
                                                <input type="submit" name="createTopic" class="btn btn-success" value="Thêm Menu">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                                <div class="card">
                                    <div class="card-header" id="headingPage">
                                        <a class="d-block" data-toggle="collapse"
                                            data-target="#collapsePage" aria-expanded="true"
                                            aria-controls="collapsePage">
                                            <label class="text-dark">Trang đơn</label>
                                        </a>
                                    </div>
                                    <div id="collapsePage" class="collapse"
                                        aria-labelledby="headingPage" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @foreach ($list_page as $page)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" name="pageid[]" type="checkbox" value="{{ $page->id }}" id="page{{ $page->id }}">
                                                <label class="form-check-label" for="page{{ $page->id }}">
                                                    {{ $page->title }}
                                                </label>
                                            </div>
                                            @endforeach
                                            <div class="mb-3">
                                                <input type="submit" name="createPage" class="btn btn-success" value="Thêm Menu">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                                <div class="card">
                                    <div class="card-header" id="headingCustom">
                                        <a class="d-block" data-toggle="collapse"
                                            data-target="#collapseCustom" aria-expanded="true"
                                            aria-controls="collapseCustom">
                                            <label class="text-dark">Tùy liên kết</label>
                                        </a>
                                    </div>
                                    <div id="collapseCustom" class="collapse"
                                        aria-labelledby="headingCustom" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="name">Tên menu</label>
                                                <input type="text" value="" name="name" id="name" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="parent_id">Danh mục cha</label>
                                                <select name="parent_id" id="parent_id" class="form-control">
                                                    <option value="0">None</option>
                                                    {!! $htmlparentidmenu !!}
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="sort_order">Sắp xếp</label>
                                                <select name="sort_order" id="sort_order" class="form-control">
                                                    <option value="0">None</option>
                                                    {!! $htmlsortordermenu !!}
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="link">Liên kết</label>
                                                <input type="text" value="" name="link" id="link" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <input type="submit" name="createCustom" class="btn btn-success" value="Thêm Menu">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                                <div class="card p-3">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="2">Chưa xuất bản</option>
                                        <option value="1">Xuất bản</option>
                                    </select>
                                </div>
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
                                    <th class="text-center" style="width:160px;">Tên menu</th>
                                    <th class="text-center" style="width:120px;">Link</th>
                                    <th class="text-center" style="width:50px;">Type</th>
                                    <th class="text-center" style="width:50px;">Vị trí</th>      
                                    <th class="text-center" style="width:200px;">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $row)
                                    <tr class="datarow">
                                        <td><input type="checkbox"></td>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td class="text">{{ $row->link }}</td>
                                        <td>{{ $row->type }}</td>
                                        <td>{{ $row->position }}</td>
                                        @php
                                            $args = ['id' => $row->id];
                                        @endphp
                                        <td class="text-center">
                                            @if ($row->status == 1)
                                            <a href="{{ route('admin.menu.status',$args) }}" class="btn btn-success" style="width:40px;"><i class="fas fa-toggle-on"></i></a>
                                        @else
                                            <a href="{{ route('admin.menu.status',$args) }}" class="btn btn-danger" style="width:40px;"><i class="fas fa-toggle-off"></i></a>
                                        @endif
                                                <a href="{{ route('admin.menu.edit',$args) }}" class="btn btn-primary" style="width:40px;"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.menu.show',$args) }}" class="btn btn-info" style="width:40px;"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.menu.delete',$args) }}" class="btn btn-danger" style="width:40px;"><i class="fas fa-trash"></i></a>
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
    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;
        
        for (i = 0; i < acc.length; i++) {
          acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
              panel.style.display = "none";
            } else {
              panel.style.display = "block";
            }
          });
        }
        </script>
@endsection
