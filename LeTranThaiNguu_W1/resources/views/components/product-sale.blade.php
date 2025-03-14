<section id="product-sale" class="p-3">
    <h2 class="text-center text-danger">
        <i class="fas fa-bolt"></i> FLASH SALE
    </h2>
    <div class="row">
        @foreach ($product_sale as $product_item)
        <div class="col-sm-3 my-2">
            <a href="chi-tiet-san-pham/1">
                <x-product-card :productitem="$product_item" />
            </a>
        </div>
        @endforeach
    </div>
</section>