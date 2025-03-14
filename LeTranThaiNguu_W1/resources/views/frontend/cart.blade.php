@extends('layouts.site')
@section('title','Giỏ hàng')
@section('content')
<div class="container my-5">
    <div class="container">
        <h1 class="mb-4 text-center text-success">
            <i class="bi bi-cart"></i> GIỎ HÀNG CỦA TÔI
        </h1>
    </div>
    <form action="{{ route('site.cart.update') }}" method="post">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>Mã</th>
                        <th>Hình</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                     $totalMoney=0;   
                    @endphp
                    @foreach ($list_cart as $index => $row_cart)
                    <tr>
                        <td class="text-center">{{ $row_cart['id'] }}</td>
                        <td>
                            <img class="img-fluid cart-img" src="{{ asset('images/products/'.$row_cart['image']) }}" alt="{{ $row_cart['image'] }}">
                        </td>
                        <td>{{ $row_cart['name'] }}</td>
                        <td>{{ number_format($row_cart['price'], 0, ',', '.') }} VND</td>
                        <td>
                            <div class="input-group quantity-input">
                                <button class="btn btn-outline-secondary" type="button" onclick="decreaseQty({{ $index }})">-</button>
                                <input style="width: 60px;" type="number" min="0" value="{{ $row_cart['qty'] }}" name="qty[{{ $row_cart['id'] }}]" id="qty-{{ $index }}" class="form-control text-center">
                                <button class="btn btn-outline-secondary" type="button" onclick="increaseQty({{ $index }})">+</button>
                            </div>
                        </td>
                        <td>{{ number_format($row_cart['price'] * $row_cart['qty'], 0, ',', '.') }} VND</td>
                        <td>
                            <a href="{{ route('site.cart.delete',['id'=>$row_cart['id']]) }}" class="btn btn-danger btn-sm">X</a>
                        </td>
                    </tr>
                    @php
                        $totalMoney += $row_cart['price'] * $row_cart['qty']
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">
                            <a class="btn btn-success px-3" href="{{ route('site.home') }}">Mua thêm</a>
                            <button type="submit" class="btn btn-info px-3 text-light">Cập nhật</button>
                            <a class="btn btn-warning px-3 text-light" href="{{ route('site.cart.checkout') }}">Thanh toán</a>
                        </th>
                        <th colspan="3" class="text-end text-danger"><strong>Tổng tiền: {{ number_format($totalMoney) }} VND </strong></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>
    
</div>

<script>
    function decreaseQty(index) {
        let qtyInput = document.getElementById(`qty-${index}`);
        if (qtyInput.value > 0) {
            qtyInput.value--;
        }
    }

    function increaseQty(index) {
        let qtyInput = document.getElementById(`qty-${index}`);
        qtyInput.value++;
    }

    function removeFromCart(productId) {
        // Implement AJAX request to remove item from cart
        alert(`Remove product with ID: ${productId}`);
    }
</script>

<style>
    .cart-img {
        max-width: 60px;
        max-height: 60px;
    }
    .quantity-input {
        display: flex;
        align-items: center;
    }
    .quantity-input .btn {
        padding: 5px 10px;
    }
    .quantity-input input {
        text-align: center;
    }
</style>
@endsection
