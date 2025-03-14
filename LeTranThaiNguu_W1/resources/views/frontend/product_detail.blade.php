@extends('layouts.site')
@section('title', 'Chi tiết sản phẩm')
@section('content')
<section class="hdl-maincontent py-2">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="image">
                    <img id="productimage" class="img-fluid w-100" src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->image }}">
                </div>
                <script>
                    function changeimage(src) {
                        document.getElementById("productimage").src = src;
                    }
                </script>
            </div>
            <div class="col-md-6">
                <h1 class="text-main">{{ $product->name }}</h1>
                <h2 class="text-danger py-4">
                    @if ($product->pricesale > 0 && $product->pricesale < $product->price)
                    <div class="col-8">
                        Giá: {{ number_format($product->pricesale) }} <del>{{ number_format($product->price) }}</del>
                    </div>
                    @else
                    <div class="col-8">Giá: {{ number_format($product->price) }}</div>
                    @endif
                </h2>
                <div class="input-group mb-3">
                    <input type="number" value="1" min="0" aria-describedby="basic-addon2" id="qty">
                    <button onclick="handleAddCart({{ $product->id }})" class="input-group-text text-light bg-info" id="basic-addon2">ĐẶT MUA</button>
                </div>
                <h3>Mô tả sản phẩm</h3>
                <p>{{ $product->description }}</p>
            </div>
        </div>
        <div class="row">
            <h2 class="text-main fs-4 pt-4">Chi tiết sản phẩm</h2>
            <p>{{ $product->detail }}</p>
        </div>
        <div class="row">
            <h2 class="text-main fs-4 pt-4">Sản phẩm khác</h2>
            <div class="row product-list">
                @foreach ($list_product as $productitem)
                <div class="col-6 col-md-3 mb-4">
                    <x-product-card :$productitem />
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer')
<script>
    function handleAddCart(productid) {
        let qty = document.getElementById("qty").value;
        $.ajax({    
            url: "{{ route('site.addcart') }}",
            type: "GET",
            data: {
                productid: productid,
                qty: qty
            },
            success: function(result, status, xhr) {
                document.getElementById("showqty").innerHTML = result;
            },
            error: function(xhr, status, error) {
                alert(error);
            },
        });
    }
</script>
@endsection
