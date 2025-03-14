@extends('layouts.site')
@section('title','Thanh toán')
@section('content')
<div class="container my-5">
    <div class="container">
        <h1 class="mb-4 text-center text-success">
            <i class="bi bi-cart"></i> THANH TOÁN
        </h1>
    </div>
    <div class="row">
        <div class="col-md-9">
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
                                    {{ $row_cart['qty'] }}
                                </td>
                                <td>{{ number_format($row_cart['price'] * $row_cart['qty'], 0, ',', '.') }} VND</td>
                            </tr>
                            @php
                                $totalMoney += $row_cart['price'] * $row_cart['qty']
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
        <div class="col-md-3">
            <strong>Tổng tiền: {{ number_format($totalMoney) }} VND </strong>
        </div>
    </div>
    @if (!Auth::check())
    <div class="row">
        <div class="col-12">
            <h3>Hãy đăng nhập để thanh toán</h3>
            <a href="{{ route('website.getlogin') }}">Đăng nhập</a>
        </div>
    </div>
    @else
    <form action="{{ route('site.cart.docheckout') }}" method="post">
        @csrf
        <div class="row">
            @php
                $user = Auth::user();
            @endphp
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="text" name="email" value="{{ $user->email }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Điện thoại</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Địa chỉ</label>
                    <input type="text" name="address" value="{{ $user->address }}" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label>Chú ý</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-12 text-end">
                <button class="btn btn-success" type="submit">Đặt mua</button>
            </div>
        </div>
    </form>
    @endif
    
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
