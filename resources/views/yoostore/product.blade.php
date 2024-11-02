@extends('yoostore.layout.master')
@section('css')
<link href="{{ asset('yoostore/css/product.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- section 1 -->
<div class="container w-75 my-5" style="height: 778px">

@if ($product->typeDetails->first()->typename !== null)
<livewire:product.add-to-cart>
    
@else
<livewire:product.add-to-cart2>
@endif

</div>

<!-- section 2 -->
<div class="d-flex justify-content-center">

    <div class="product-details">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#description">Description</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#additional-info">Additional Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-none" data-toggle="tab" href="#specification">Specification</a>
            </li>
            <li class="nav-item d-none">
                <a class="nav-link" data-toggle="tab" href="#review">Review</a>
            </li>
        </ul>

        <div class="details">
            <div class="tab-content w-50">
                <div id="description" class="tab-pane fade show active">
                    <h5>Description</h5>
                    <p>
                        {{ $product->description }}
                    </p>
                </div>

                <div id="additional-info" class="tab-pane fade">
                    <h5>Additional Information</h5>
                    <p>{{ $product->longdescription }}.</p>
                </div>

                <div id="specification" class="tab-pane fade d-none">
                    <h5>Specification</h5>
                    <p>Content for specification goes here.</p>
                </div>

                <div id="review" class="tab-pane fade d-none">
                    <h5>Review</h5>
                    <p>Content for review goes here.</p>
                </div>
            </div>

            <div class="feature-section w-25">
                <h5>Feature</h5>
                <ul>
                    <li class="d-none">
                        <i class="fa-solid fa-shield-alt "></i> Free 1 Year Warranty
                    </li>
                    <li class="d-none">
                        <i class="fa-solid fa-truck"></i> Fasted Delivery
                    </li>
                    <li>
                        <i class="fa-solid fa-money-check-alt"></i> 100% Money-back guarantee
                    </li>
                    <li>
                        <i class="fa-solid fa-headset"></i> 24/7 Customer support
                    </li>
                    <li>
                        <i class="fa-solid fa-lock"></i> Secure payment method
                    </li>
                </ul>
            </div>

            <div class="shipping-info w-25">
                <h5>Shipping Information</h5>
                <p class="text-muted"><strong>Courier:</strong> {{ $product->deliverytime}} days</p>
            </div>
        </div>
    </div>
</div>



<!-- section 3 -->
<div class="container my-4 w-75">
    <div class="row justify-content-center">
        <!-- First Card -->
        @foreach ($products as $product)
        <div class="col-3">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
                <div style="height: 66.67%; overflow: hidden;">
                    <img src="{{ asset('storage/' . optional($product->images->first())->image_path) }}" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;" />
                </div>
                <div class="card-body" style="height: 33.33%;">
                    <div class="rating my-2">
                        <span class="text-warning">★★★★★</span>
                        <span>(738)</span>
                    </div>
                    <h4 class="card-title" style="font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <a href="{{ route('product', $product->id) }}" style="text-decoration: none; color: black">{{ $product->name }}</a>
                    </h4>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection