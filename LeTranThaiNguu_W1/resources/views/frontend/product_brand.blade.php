@extends('layouts.site')
@section('title', $brand->name ?? 'Thương hiệu')
@section('content')
    <section class="hdl-maincontent py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="accordion mb-4" id="categoryAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="categoryHeading">
                                <button class="accordion-button collapsed bg-warning text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                                    DANH MỤC SẢN PHẨM
                                </button>
                            </h2>
                            <div id="collapseCategory" class="accordion-collapse collapse show" aria-labelledby="categoryHeading" data-bs-parent="#categoryAccordion">
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
                    <div class="accordion" id="brandAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="brandHeading">
                                <button class="accordion-button collapsed bg-success text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="false" aria-controls="collapseBrand">
                                    DANH MỤC THƯƠNG HIỆU
                                </button>
                            </h2>
                            <div id="collapseBrand" class="accordion-collapse collapse" aria-labelledby="brandHeading" data-bs-parent="#brandAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($brands as $brand_item)
                                            <li class="list-group-item">
                                                <a href="{{ route('site.product.brand', ['slug' => $brand_item->slug]) }}">{{ $brand_item->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="bg-info p-3 mb-4 rounded">
                        <h3 class="fs-5 py-3 text-center text-light">{{ $brand->name ?? 'Thương hiệu' }}</h3>
                    </div>
                    <section class="hdl-maincontent py-2">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <label for="filter" class="form-label">Filter:</label>
                                <select id="filter" class="form-select">
                                    <option selected>Choose...</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="sort" class="form-label">Sắp xếp theo:</label>
                                <select id="sort" class="form-select" onchange="location = this.value;">
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>  
                            <div class="col-lg-6 d-flex justify-content-end align-items-center">
                                <button id="grid-view-btn" class="btn btn-secondary bg-warning" onclick="switchView('grid')">Grid view</button>
                                <button id="list-view-btn" class="btn btn-secondary me-2 bg-warning" onclick="switchView('list')">List view</button>
                            </div>
                        </div>
                    </section>
                    <div class="product-category mt-4">
                        <div class="row">
                            @foreach ($list_product as $productitem)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
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
        const brandSlug = '{{ $brand->slug }}'; // Thay đổi từ $row->slug thành $brand->slug
        const urlParams = new URLSearchParams(window.location.search);
        
        if (view === 'list') {
            window.location.href = `{{ url('thuong-hieu') }}/${brandSlug}/list?` + urlParams.toString();
        } else if (view === 'grid') {
            window.location.href = `{{ url('thuong-hieu') }}/${brandSlug}/grid?` + urlParams.toString();
        }
    }
</script>