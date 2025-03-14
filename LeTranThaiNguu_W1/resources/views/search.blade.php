@extends('layouts.site')
@section('title', 'Sản phẩm')
@section('content')
<h1>Kết quả tìm kiếm</h1>

@if ($products->isEmpty())
    <p>Không tìm thấy sản phẩm nào.</p>
@else
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4 mb-4">
                <x-product-card :productitem="$product" />
            </div>
        @endforeach
    </div>
@endif
@endsection
