@extends('yoostore.layout.master')
@section('css')
    <link href="{{ asset('yoostore/css/product.css')}}" rel="stylesheet">
@endsection
@section('content')
   <!-- section 1 -->
   <div class="container w-75 my-5" style="height: 778px">
     
    <livewire:product.add-to-cart>

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