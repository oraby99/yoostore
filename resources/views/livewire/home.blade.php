<div class="container my-4 w-75 " style="height: auto;">
    <div class="w-100 d-flex justify-content-center m-4">

        <div class="search-bar">
            <input type="text" placeholder="Search for anything..." wire:model="search" />
            <i class="fa-solid fa-magnifying-glass" style="cursor: pointer;" hover="color: #fa8232;" wire:click="searchProducts"></i>
        </div>
    </div>
    @if (session()->has('error'))
    <div class="d-flex justify-content-center w-100">

        <div class="alert alert-danger mt-3 w-25 d-flex justify-content-center">
            {{ session('error') }}
        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <!-- First Card -->
        @foreach ($products as $product)
        <div class="col-3">
            <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                @if ($product->in_stock == 0)
                <span class="badge bg-dark position-absolute top-0 start-0 m-2">Out of stock</span>
                @else
                <span class="badge bg-success position-absolute top-0 start-0 m-2">In stock</span>
                @endif
                <div style="height: 66.67%; overflow: hidden;">
                    <img src="{{ optional($product->images->first())->image_path }}" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;" />
                </div>
                <div class="card-body" style="height: 33.33%;">
                    <div class="rating my-2">
                        @php
                        $averageRating = $product->rates()->avg('rate');
                        @endphp
                        <span class="text-warning">
                            {{ str_repeat('★', floor($averageRating)) . str_repeat('☆', 5 - floor($averageRating)) }}</span>
                        <span>{{( $averageRating) }}</span>
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