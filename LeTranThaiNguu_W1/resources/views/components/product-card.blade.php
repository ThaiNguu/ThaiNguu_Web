<div class="card card-custom">
    <a href="{{ route('site.product.detail',['slug'=>$product->slug]) }}">
    <img src="{{ asset('images/products/'.$product->image) }}" alt="{{ $product->image }}" class="card-img-top" style="width:100%">
    <div class="card-body">
        <h4 class="card-title"><b>{{ $product->name }}</b></h4>
        <div class="price_sale text-danger">
            <div class="row">
                @if ($product->pricesale > 0 && $product->pricesale < $product->price)
                    <div class="col-8">{{ number_format($product->pricesale) }}<del>{{ number_format($product->price) }}</del></div>
                @else
                <div class="col-8">{{ number_format($product->price) }}</div>   
                @endif
            </div>
        </div>
    </div>
    </a>
</div>
