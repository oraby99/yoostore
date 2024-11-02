@extends('yoostore.layout.master')
@section('css')
<link rel="stylesheet" href="{{asset('yoostore/css/setting.css') }}" />
@endsection
@section('content')

@php
    $user = Auth::user();
    $products = App\Models\Favorite::where('user_id', $user->id)->get();
@endphp
<div class="p-5">

    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
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
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
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
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
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
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
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
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
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
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
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
        <div class="col-md-3 mb-5">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
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

@endsection