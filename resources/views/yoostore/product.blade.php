@extends('yoostore.layout.master')
@section('css')
    <link href="{{ asset('yoostore/css/product.css')}}" rel="stylesheet">
@endsection
@section('content')
   <!-- section 1 -->
   <div class="container w-75 my-5" style="height: 778px">
      <div class="container product-page">
        <div class="row">
          <!-- Product Image Section -->
          <div class="col-md-6 order-md-1 product-images">
            <div class="main-image mb-3 ">
              <img
                src="{{ asset('yoostore/images/headphone1.jpeg') }}"
                alt="Main Product"
                
              />
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
                <p><span>Availability</span>: Instock</p>
                <p><span>Category</span>: {{$product->category->name}}</p>
              </div>
            </div>

            <div class="price" style="font-weight: 500; color: #2da5f3">
              240 KWD
              <span class="old-price" style="font-weight: 500">320 KWD</span>
              <span class="discount" style="color: #efd33d">21% OFF</span>
            </div>
            <div class="dropdowns">
              <!-- Color and Memory in one column, Size and Storage in another -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="mb-3 color">
                    <label for="color">Color:</label>
                    <span class="rounded-circle"></span>
                  </div>
                  <div class="mb-3">
                    <label for="memory">Memory:</label>
                    <select id="memory" class="custom-select">
                      <option>16GB Unified Memory</option>
                      <option>32GB Unified Memory</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="size">Size:</label>
                    <select id="size" class="custom-select">
                      <option>14-inch Liquid Retina XDR Display</option>
                      <option>16-inch Liquid Retina XDR Display</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="storage">Storage:</label>
                    <select id="storage" class="custom-select">
                      <option>1TB SSD Storage</option>
                      <option>2TB SSD Storage</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quantity, Add to Cart, and Buy Now in 1:2:1 layout -->
            <div class="button-group mb-3">
              <div class="quantity-control d-flex">
                <div class="input-group quantity-box">
                  <button class="btn btn-outline-secondary">-</button>
                  <input
                    type="text"
                    value="1"
                    class="form-control text-center"
                  />
                  <button class="btn btn-outline-secondary">+</button>
                </div>
              </div>

              <button
                class="btn btn-warning add-to-cart"
                style="color: aliceblue; font-weight: 600"
              >
                ADD TO CART <i class="fa-solid fa-cart-shopping"></i>
              </button>
              <button
                class="btn btn-light buy-now"
                style="color: #fa8232; font-weight: 600"
              >
                BUY NOW
              </button>
            </div>

            <div class="row mb-3 align-items-center">
              <div class="col-md-4">
                <button class="btn btn-link btn-sm me-2">
                  <i class="fa-solid fa-heart"></i> Add to Wishlist
                </button>
                <button class="btn btn-link btn-sm me-2">
                  <i class="fa-solid fa-arrows-rotate"></i> Add to Compare
                </button>
              </div>

              <div class="col-md-7 text-end">
                <span class="me-2" style="font-size: 0.9rem"
                  >Share product:</span
                >
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
                     <a class="nav-link" data-toggle="tab" href="#specification">Specification</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#review">Review</a>
                 </li>
             </ul>
         
             <div class="details">
                 <div class="tab-content w-50">
                     <div id="description" class="tab-pane fade show active">
                         <h5>Description</h5>
                         <p>
                             The most powerful MacBook Pro ever is here. With the blazing-fast M1 Pro or M1 Max chip — the first Apple silicon designed for pros — you get groundbreaking performance and amazing battery life. Add to that a stunning Liquid Retina XDR display, the best camera and audio ever in a Mac notebook, and all the ports you need. The first notebook of its kind, this MacBook Pro is a beast. M1 Pro takes the exceptional performance of the M1 architecture to a whole new level for pro users.
                         </p>
                     </div>
         
                     <div id="additional-info" class="tab-pane fade">
                         <h5>Additional Information</h5>
                         <p>Content for additional information goes here.</p>
                     </div>
         
                     <div id="specification" class="tab-pane fade">
                         <h5>Specification</h5>
                         <p>Content for specification goes here.</p>
                     </div>
         
                     <div id="review" class="tab-pane fade">
                         <h5>Review</h5>
                         <p>Content for review goes here.</p>
                     </div>
                 </div>
         
                 <div class="feature-section w-25">
                     <h5>Feature</h5>
                     <ul>
                         <li>
                             <i class="fa-solid fa-shield-alt "></i> Free 1 Year Warranty
                         </li>
                         <li>
                             <i class="fa-solid fa-truck"></i> Free Shipping & Fasted Delivery
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
                     <p class="text-muted"><strong>Courier:</strong> 2-4 days, free shipping</p>
                     <p class="text-muted"><strong>International Shipping:</strong> up to two weeks, 15 KWD</p>
                 </div>
             </div>
         </div>
     </div>



     <!-- section 3 -->
     <div class="container my-4 ">
        <div class="row justify-content-center">
            <!-- First Card -->
            <div class="col-3">
                <div class="card custom-card">
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">HOT</span>
                    <img src="images/headphone1.jpeg" class="card-img-top" alt="Smartphone" >
                    <div class="card-body ">
                        <div class="rating my-2">
                            <span class="text-warning">★★★★★</span>
                            <span>(738)</span>
                        </div>
                        <h5 class="card-title">TOZO T6 True Wireless Earbuds Bluetooth Headphones</h5>
                        <h4 class="pricee">70 KWD</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection