@extends('yoostore.layout.master')
@section('css')
<link rel="stylesheet" href="{{asset('yoostore/css/setting.css') }}" />
@endsection
@section('content')

@php
$user = Auth::user();
$products = App\Models\Favorite::where('user_id', $user->id)->get();
@endphp

@if (count($products) == 0)
    <h1 class="text-center mt-5 " style="font-size: 40px; height: 200px; color: #fa8232" >Your wishlist is Empty</h1>
@else
<div class="p-5">
    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                @if ($product->product->in_stock == 0)
                <span class="badge bg-dark position-absolute top-0 start-0 m-2">Out of stock</span>
                @else
                <span class="badge bg-success position-absolute top-0 start-0 m-2">In stock</span>
                @endif
                <div style="height: 66.67%; overflow: hidden;">
                    <img src="{{ asset('storage/' . optional($product->product->images->first())->image_path) }}" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;" />
                </div>
                <div class="card-body" style="height: 33.33%;">
                    <div class="rating my-2">
                        <span class="text-warning">★★★★★</span>
                        <span>(738)</span>
                    </div>
                    <h4 class="card-title" style="font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <a href="{{ route('product', $product->product->id) }}" style="text-decoration: none; color: black">{{ $product->product->name }}</a>
                    </h4>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection