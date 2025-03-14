@extends('layouts.site')
@section('title', 'Sản phẩm')
@section('content')
    <section class="hdl-maincontent py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Accordion for product categories -->
                    <div class="accordion mb-4" id="productCategoriesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="productCategoriesHeading">
                                <button class="accordion-button collapsed bg-warning text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProductCategories" aria-expanded="true" aria-controls="collapseProductCategories">
                                    DANH MỤC SẢN PHẨM
                                </button>
                            </h2>
                            <div id="collapseProductCategories" class="accordion-collapse collapse show" aria-labelledby="productCategoriesHeading" data-bs-parent="#productCategoriesAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($categories as $category)
                                            <li class="list-group-item">
                                                <a href="{{ route('site.product.category', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Accordion for brand categories -->
                    <div class="accordion" id="brandCategoriesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="brandCategoriesHeading">
                                <button class="accordion-button collapsed bg-success text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBrandCategories" aria-expanded="false" aria-controls="collapseBrandCategories">
                                    DANH MỤC THƯƠNG HIỆU
                                </button>
                            </h2>
                            <div id="collapseBrandCategories" class="accordion-collapse collapse" aria-labelledby="brandCategoriesHeading" data-bs-parent="#brandCategoriesAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($brands as $brand)
                                            <li class="list-group-item">
                                                <a href="{{ route('site.product.brand', ['slug' => $brand->slug]) }}">{{ $brand->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-9">
                    <!-- Product listing section -->
                    <div class="bg-info p-3 mb-4 rounded">
                        <h3 class="fs-5 py-3 text-center text-light">TẤT CẢ SẢN PHẨM</h3>
                    </div>
                    
                    <!-- Filters section -->
                    <section class="hdl-maincontent py-2">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <label for="price_min" class="form-label">Giá từ:</label>
                                <input type="number" id="price_min" name="price_min" class="form-control" value="{{ request('price_min') }}" min="0">
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="price_max" class="form-label">Đến:</label>
                                <input type="number" id="price_max" name="price_max" class="form-control" value="{{ request('price_max') }}" min="0">
                            </div>
                            <button id="apply-filter-btn" class="btn btn-secondary bg-warning" onclick="applyFilters()">Lọc</button>
                            <div class="col-lg-3 mb-3">
                                <label for="sort" class="form-label">Sắp xếp theo:</label>
                                <select id="sort" class="form-select" onchange="applyFilters()">
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>  
                            <div class="col-lg-3 d-flex justify-content-end align-items-center">
                                
                                <button id="grid-view-btn" class="btn btn-secondary bg-warning ms-2" onclick="switchView('grid')">Grid view</button>
                                <button id="list-view-btn" class="btn btn-secondary bg-warning ms-2" onclick="switchView('list')">List view</button>
                            </div>
                        </div>
                    </section>
                    
                    <!-- Product listing -->
                    <div class="product-category mt-4">
                        <div class="row" id="product-container">
                            @foreach ($list_product as $productitem)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-item">
                                    <x-product-card :productitem="$productitem" />
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-center">
                                {{ $list_product->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


<script>
    function switchView(view) {
        if (view === 'list') {
            window.location.href = '{{ route("site.products.list") }}';
        } else if (view === 'grid') {
            window.location.href = '{{ route("site.products.grid") }}';
        }
    }

    function applyFilters() {
        var price_min = document.getElementById('price_min').value;
        var price_max = document.getElementById('price_max').value;
        var sort = document.getElementById('sort').value;
        
        var url = '{{ route("site.product.index") }}';
        var params = [];

        if (price_min !== '') {
            params.push('price_min=' + price_min);
        }
        if (price_max !== '') {
            params.push('price_max=' + price_max);
        }
        if (sort !== '') {
            params.push('sort=' + sort);
        }
        
        if (params.length > 0) {
            url += '?' + params.join('&');
        }
        
        window.location.href = url;
    }
</script>

