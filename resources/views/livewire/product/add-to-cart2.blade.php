<div class="container product-page">
    <div class="row">
        <!-- Product Image Section -->
        <div class="col-md-6 order-md-1 product-images">
            <div class="product-main-image text-center mb-3 d-flex justify-content-center">
                <img id="mainImage" src="{{ asset('storage/' . optional($product->productDetails->first())->image) }}" alt="Main Product Image" style="width: 100%; max-width: 400px; height: auto; object-fit: contain;" />
            </div>
            <div class="product-thumbnails d-flex justify-content-center">
                @foreach ($product->productDetails as $image)
                <img src="{{ asset('storage/' . $image->image) }}" alt="Thumbnail" onclick="changeMainImage(this)" style="width: 80px; height: 80px; object-fit: cover; margin: 0 5px; cursor: pointer;" />
                @endforeach
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="col-md-6 order-md-2 product-info">
            <div class="rating mb-3">
                @if ($rating > 0 && $rating = 1)
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                @elseif ($rating > 0 && $rating = 2)
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                @elseif ($rating > 0 && $rating = 3)
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                @elseif ($rating > 0 && $rating = 4)
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                @elseif ($rating > 0 && $rating = 5)
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                @endif
                <span> {{ $rating }} (Feedback)</span>

            </div>

            <h5 style="margin-bottom: 25px">
                {{$product->name}}
            </h5>
            <div class="row info">
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
                {{ $selectedPrice }} EGP
                @if ($product->discount > 0)
                <span class="old-price" style="font-weight: 500">{{ $selectedPrice + $product->discount }} EGP</span>
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
                                <option value="{{ $variation->id }}">{{ $variation->color }}</option>
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

                        <input
                            type="text"
                            class="form-control text-center"
                            wire:model="quantity"
                            readonly />

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
                    class="btn btn-light buy-now d-none"
                    style="color: #fa8232; font-weight: 600">
                    BUY NOW
                </button>
            </div>



            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                    <button class="btn btn-link btn-sm me-2" wire:click="addToWishlist({{ $product->id }})>
                        <i class=" fa-solid fa-heart"></i> Add to Wishlist
                    </button>
                </div>

                <div class="col-md-7 text-end">
                    <span class="me-2" style="font-size: 0.9rem">Share product:</span>
                    <button class="btn btn-link btn-sm me-1" onclick="copyUrlToClipboard()" wire:click="copied()">
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


            @if(session()->has('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif

            <div class="paymentIMages">
                <p>100% Guarantee Safe Checkout</p>
                <div class="d-flex">
                    <span><img src="{{ asset('yoostore/images/amrican express.png') }}" alt="" /></span>
                    <span><img src="{{ asset('yoostore/images/master card.png') }}" alt="" /></span>
                    <span><img src="{{ asset('yoostore/images/paypall.png') }}" alt="" /></span>
                    <span><img src="{{ asset('yoostore/images/visa.png') }}" alt="" /></span>
                </div>
            </div>
            <livewire:rating.rating>
        </div>
    </div>
</div>








<script>
    function changeMainImage(thumbnail) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = thumbnail.src;
    }

    function copyUrlToClipboard(button) {
        const url = window.location.href;

        navigator.clipboard.writeText(url)
            .then(() => {
                button.querySelector('i').classList.add('copied');

                alert("URL copied to clipboard!");

                setTimeout(() => {
                    button.querySelector('i').classList.remove('copied');
                }, 2000);
            })
            .catch(err => {
                console.error("Failed to copy URL: ", err);
            });
    }
</script>