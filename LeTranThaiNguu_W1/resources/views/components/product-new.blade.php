<section id="product-new" class="p-3">
    <h2 class="text-center text-info">
        <i class="fas fa-star"></i> SẢN PHẨM MỚI NHẤT
    </h2>
    <div class="row">
        @foreach ($product_new as $product_item)
        <div class="col-sm-3 my-2">
            <a href="chi-tiet-san-pham/1">
                <x-product-card :productitem="$product_item" />
            </a>
        </div>
        @endforeach
    </div>
</section>