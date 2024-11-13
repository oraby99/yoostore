@extends('yoostore.layout.master')
@section('css')
<link href="{{ asset('yoostore/css/home.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="my-3">
    <div class="w-100 row col-12" style="padding: 50px;">
        @foreach ($products as $product)
        <div class="col-2">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                @if ($product->in_stock == 0)
                <span class="badge bg-dark position-absolute top-0 start-0 m-2">Out of stock</span>
                @else
                <span class="badge bg-success position-absolute top-0 start-0 m-2">In stock</span>
                @endif
                <div style="height: 66.67%; overflow: hidden;">
                    <img src="{{ asset('storage/' . optional($product->images->first())->image_path) }}" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;" />
                </div>
                <div class="card-body" style="height: 33.33%;">
                    <div class="rating my-2">
                        @php
                        $averageRating = $product->rates()->avg('rate');
                        @endphp
                        <span class="text-warning">
                            {{ str_repeat('★', floor($averageRating)) . str_repeat('☆', 5 - floor($averageRating)) }}</span>
                        <span>{{ ( $averageRating ) }}</span>
                    </div>
                    <h4 class="card-title" style="font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <a href="{{ route('product', $product->id) }}" style="text-decoration: none; color: black">{{ $product->name }}</a>
                    </h4>
                    <h4 class="pricee"></h4>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection