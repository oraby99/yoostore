@extends('yoostore.layout.master')
@section('css')
<link href="{{ asset('yoostore/css/home.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- section 1 -->
<div class="section1">
  <img src="{{ asset('yoostore/images/banner1.jpeg') }}" alt="" />
  <div>
    <p>THE BEST PLACE TO PLAY</p>
    <p class="p1">Summer Sale</p>
    <p class="p2">
      Save up to 50% on select Xbox games. Get 3 months of PC Game Pass for
      10 KWD.
    </p>
    <button>shop now <i class="fa-solid fa-arrow-right mx-3"></i></button>
  </div>
</div>

<!-- section2 -->
<div class="container my-4 w-75" style="height: 108">
  <div class="row text-center justify-content-center feature-section">
    <div class="col-3 d-flex align-items-center justify-content-center">
      <div class="feature-item">
        <i class="fa-solid fa-trophy mx-4"></i>
        <div>
          <h6>FAST DELIVERY</h6>
          <p>Quick Delivery to your doorstep</p>
        </div>
      </div>
    </div>
    <div class="col-1 text-center divider"></div>
    <div class="col-3 d-flex align-items-center justify-content-center">
      <div class="feature-item">
        <i class="fa-solid fa-trophy mx-4"></i>
        <div>
          <h6>REFUND & EXCHANGE</h6>
          <p>100% money-back guarantee</p>
        </div>
      </div>
    </div>
    <div class="col-1 text-center divider"></div>
    <div class="col-3 d-flex align-items-center justify-content-center">
      <div class="feature-item">
        <i class="fa-solid fa-trophy mx-4"></i>
        <div>
          <h6>SECURE PAYMENT</h6>
          <p>Secure Online Payment</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- section 3 -->
<div class="d-flex justify-content-center">
  <div
    id="simpleSlider"
    class="carousel slide"
    data-bs-ride="carousel"
    style="width: 80%">
    <div class="carousel-indicators">
      <button
        type="button"
        data-bs-target="#simpleSlider"
        data-bs-slide-to="0"
        class="active"
        aria-current="true"
        aria-label="Slide 1"></button>
      <button
        type="button"
        data-bs-target="#simpleSlider"
        data-bs-slide-to="1"
        aria-label="Slide 2"></button>
      <button
        type="button"
        data-bs-target="#simpleSlider"
        data-bs-slide-to="2"
        aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img
          src="{{ asset('yoostore/images/master card.png') }}"
          class="d-block w-100"
          alt="Slide 1" />
      </div>
      <div class="carousel-item">
        <img
          src="{{ asset('yoostore/images/master card.png') }}"
          class="d-block w-100"
          alt="Slide 2" />
      </div>
      <div class="carousel-item">
        <img
          src="{{ asset('yoostore/images/amrican express.png') }}"
          class="d-block w-100"
          alt="Slide 3" />
      </div>
    </div>
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#simpleSlider"
      data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#simpleSlider"
      data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>

<!-- section 4 -->

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

<!-- section 5 -->

<div
  class="section5 container my4 w-75 mt-5 d-flex justify-content-between">
  <div class=" w-25 mx-4">
    <div class="banner2 p-5">
      <p class="text-danger text-center">COMPUTER & ACCESSORIES</p>
      <p class="text-center" style="font-size: 32px">32% Discount</p>
      <p class="text-center">For all ellectronics products</p>
      <span style="font-size: 13px">Offers ends in</span><span
        style="background-color: white; font-size: 13px; font-weight: 600; p-1">ENDS OF CHRISTMAS</span>
      <button>shop now <i class="fa-solid fa-arrow-right mx-3"></i></button>
    </div>
    <div>
      <img src="{{ asset('yoostore/images/banner 2.jpeg') }}" alt="" />
    </div>
  </div>

  <div class="products w-75 p-3">
    <div class="d-flex justify-content-between">
      <span style="font-size: 22px; font-weight: 600">Featuerd Products</span>
      <div class="featuerd">
        @foreach($categories as $category)
        @foreach($category->subcategories as $subcategory)
        <span class="featureitem">{{ $subcategory->name }}</span>
        @endforeach
        @endforeach


        <span style="color: #fa8232">View All Products <i class="fa-solid fa-arrow-right-long"></i></span>
      </div>
    </div>


    <div class="my-3">
      <div class="w-100 row col-12">
        @foreach ($products as $product)
        <div class="col-4">
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
              <h4 class="pricee"></h4>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>


  </div>
</div>

<!-- section 6 -->
<div class="d-flex justify-content-center my-5">
  <div class="section6 d-flex justify-content-around">
    <div class="p-5">
      <div
        style="
              height: 35px;
              background-color: #efd33d;
              font-weight: 600;
              width: 170px;
            "
        class="d-flex justify-content-center align-items-center">
        <p>INTRODUCING NEW</p>
      </div>
      <p
        style="
              font-size: 32px;
              width: 280px;
              font-weight: 600;
              color: white;
            ">
        Xiaomi Mi 11 Ultra 12GB+256GB
      </p>
      <p style="width: 280px; font-weight: 600; color: #adb7bc">
        *Data provided by internal laboratories. Industry measurment.
      </p>
      <button>Shop Now <i class="fa-solid fa-arrow-right mx-3"></i></button>
    </div>
    <div class="">
      <img src="{{ asset('yoostore/images/xaomi.png') }}" alt="" />
    </div>
  </div>
</div>

<!-- section 7 -->

<div
  class="section5 container my4 w-75 mt-5 d-flex justify-content-between">
  <div class="products w-75 p-3">
    <div class="d-flex justify-content-between">
      <span style="font-size: 22px; font-weight: 600">Featuerd Products</span>
      <div class="featuerd">
        @foreach($categories as $category)
        @foreach($category->subcategories as $subcategory)
        <span class="featureitem">{{ $subcategory->name }}</span>
        @endforeach
        @endforeach
        <span style="color: #fa8232">View Products <i class="fa-solid fa-arrow-right-long"></i></span>
      </div>
    </div>

    <div class="my-3">
      <div class="w-100 row col-12">
        @foreach ($products as $product)
        <div class="col-4">
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
              <h4 class="pricee"></h4>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>


  </div>

  <div class="w-25 mx-4">
    <div class="banner2 p-5" style="background-color: #124261">
      <p class="text-white text-center">COMPUTER & ACCESSORIES</p>
      <p class="text-white text-center" style="font-size: 32px">
        32% Discount
      </p>
      <p class="text-white text-center">For all ellectronics products</p>
      <span style="font-size: 13px" class="text-white text-center">OFFERS ENDS IN OF CHRISTMAS</span>
      <button>shop now <i class="fa-solid fa-arrow-right mx-3"></i></button>
    </div>
    <div>
      <img src="images/banner 2.jpeg" alt="" />
    </div>
  </div>
</div>

<!-- section 8 -->

<div class="my-5 d-flex justify-content-center">
  <img src="images/special offer.jpeg" alt="" />
</div>

<!-- section 9 -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll(".slide");
  const totalSlides = slides.length;

  function showSlide(slideIndex) {
    const slider = document.querySelector(".slider");
    slider.style.transform = `translateX(${-slideIndex * 100}%)`;
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
  }

  const featureItems = document.querySelectorAll(".feature-item");

  featureItems.forEach((item) => {
    item.addEventListener("click", () => {
      featureItems.forEach((i) => i.classList.remove("active"));
      item.classList.add("active");
    });
  });
</script>

@endsection