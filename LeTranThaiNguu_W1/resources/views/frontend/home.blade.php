@extends('layouts.site')
@section('content')
    <x-slider/>
    <div class="container">
    <x-product-sale/>
    <x-product-new/>
    </div>
    <x-last-post/>
@endsection
@section('title','Trang chủ')
