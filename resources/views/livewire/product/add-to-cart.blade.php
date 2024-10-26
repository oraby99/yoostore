<div class="container product-page">
    <div class="row">
        <!-- Product Image Section -->
        <div class="col-md-6 order-md-1 product-images">
            <div class="main-image mb-3 ">
                <img
                    src="{{ asset('yoostore/images/headphone1.jpeg') }}"
                    alt="Main Product" />
            </div>
            <div class="product-thumbnails d-flex">
                <img src="https://via.placeholder.com/96x96" alt="Thumbnail 1" />
                <img src="https://via.placeholder.com/96x96" alt="Thumbnail 2" />
                <img src="https://via.placeholder.com/96x96" alt="Thumbnail 3" />
                <img src="https://via.placeholder.com/96x96" alt="Thumbnail 4" />
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="col-md-6 order-md-2 product-info">
            <div class="rating mb-3">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <span>(21,671 User Feedback)</span>
            </div>

            <h5 style="margin-bottom: 25px">
                {{$product->name}}
            </h5>
            <div class="row info">
                <div class="col-6">
                    <p><span>SKU</span>: A26467</p>
                    <p><span>Brand</span>: HP</p>
                </div>
                <div class="col-6">
                    @if ($mainStock > 0)
                    <p><span>Availability</span>:There is {{$mainStock}} item</p>
                    </p>
                    @elseif ($mainStock == 0)
                    <p><span>Availability</span>:Out of stock</p>
                    </p>
                    @endif
                    <p><span>Category</span>: {{$product->category->name}}: {{$product->subCategory->name}}</p>
                </div>
            </div>

            <div class="price" style="font-weight: 500; color: #2da5f3">
                {{ $selectedPrice }} KWD
                @if ($product->discount > 0)
                <span class="old-price" style="font-weight: 500">{{ $selectedPrice + $product->discount }} KWD</span>
                <span class="discount d-none" style="color: #efd33d">{{ $product->discount }}% OFF</span>
                @endif
            </div>

            <div class="dropdowns mb-4" style="height: auto;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12">
                            <label for="memory">Edition:</label>
                            <select id="memory" class="custom-select" wire:change="selectVariation($event.target.value)">
                                @foreach ($product->productDetails as $variation)
                                <option value="{{ $variation->id }}">{{ $variation->typename }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($product->attributes as $key => $value)
                    <div class="mb-3 col-md-6">
                        <label>{{ ucfirst($key) }}: {{ $value }}</label>
                    </div>
                    @endforeach
                </div>
            </div>


            <!-- Quantity, Add to Cart, and Buy Now in 1:2:1 layout -->
            <div class="button-group mb-3">
                <div class="quantity-control d-flex">
                    <div class="input-group quantity-box">
                        <button class="btn btn-outline-secondary" wire:click="decrementQuantity">-</button>
                        <input type="text" class="form-control text-center" wire:model="quantity" readonly />
                        <button class="btn btn-outline-secondary" wire:click="incrementQuantity">+</button>
                    </div>
                </div>

                <button
                    class="btn btn-warning add-to-cart"
                    wire:click="addToCart"
                    style="color: aliceblue; font-weight: 600">
                    ADD TO CART <i class="fa-solid fa-cart-shopping"></i>
                </button>

                <button
                    class="btn btn-light buy-now"
                    style="color: #fa8232; font-weight: 600">
                    BUY NOW
                </button>
            </div>


            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                    <button class="btn btn-link btn-sm me-2">
                        <i class="fa-solid fa-heart"></i> Add to Wishlist
                    </button>
                    <button class="btn btn-link btn-sm me-2 d-none">
                        <i class="fa-solid fa-arrows-rotate"></i> Add to Compare
                    </button>
                </div>

                <div class="col-md-7 text-end">
                    <span class="me-2" style="font-size: 0.9rem">Share product:</span>
                    <button class="btn btn-link btn-sm me-1">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                    <button class="btn btn-link btn-sm me-1">
                        <i class="fa-brands fa-facebook"></i>
                    </button>
                    <button class="btn btn-link btn-sm me-1">
                        <i class="fa-brands fa-twitter"></i>
                    </button>
                    <button class="btn btn-link btn-sm">
                        <i class="fa-brands fa-pinterest"></i>
                    </button>
                </div>
            </div>

            <div class="paymentIMages">
                <p>100% Guarantee Safe Checkout</p>
                <div>
                    <span><img src="{{ asset('yoostore/images/amrican express.png') }}" alt="" /></span>
                    <span><img src="{{ asset('yoostore/images/master card.png') }}" alt="" /></span>
                    <span><img src="{{ asset('yoostore/images/paypall.png') }}" alt="" /></span>
                    <span><img src="{{ asset('yoostore/images/visa.png') }}" alt="" /></span>
                </div>
            </div>
        </div>
    </div>
</div>