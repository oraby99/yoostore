@php
$categories = App\Models\Category::with('subcategories')->get();
@endphp



<div>
  <footer class="FOOTER">
    <div class="FOOTER-container">
      <!-- First Column -->
      <div class="FOOTER-col">
        <h4 class="comment">Customer Supports:</h4>
        <ul>
          <li class="title">+(965) 99661696</li>
          <li class="comment">YooStore Kuwait</li>
          <li class="title_2">info@yoostore.com</li>
        </ul>
      </div>

      <!-- Second Column -->
      <div class="FOOTER-col">
        <h4 class="title">Top Category</h4>
        <ul>
          @foreach($categories as $category)
          @foreach($category->subcategories as $subcategory)
          <li><a href="#">{{ $subcategory->name }}</a></li>
          @endforeach
          @endforeach
          <li>
            <a href="#" style="color: yellow">Browse All Product <i class="fa-solid fa-arrow-right"></i></a>
          </li>
        </ul>
      </div>

      <!-- Third Column -->
      <div class="FOOTER-col">
        <h4 class="title">Quick Links</h4>
        <ul>
          <li><a href="{{ route('home') }}">Shop Product</a></li>
          <li><a href="{{ route('cart') }}">Shopping Cart</a></li>
          <li><a href="">Wishlist</a></li>
          <li><a href="{{route('track')}}">Track Order</a></li>
          <li><a href="">Customer Help</a></li>
          <li><a href="">About Us</a></li>
        </ul>
      </div>

      <!-- Fourth Column -->
      <div class="FOOTER-col cards">
        <h4 class="title">Download App</h4>
        <ul class="d-flex gap-3">
          <li>
            <i class="fa-brands fa-apple" style="font-size: 26px; color: white;"> <a href=""></a> </i>
          </li>
          <li>
            <i class="fa-brands fa-google-play " style="font-size: 26px; color: white;"> <a href=""></a> </i>
          </li>
        </ul>
      </div>

      <!-- Fifth Column -->
      <div class=" tags">
        <h4 class="title">Popular Tag</h4>
        <span class="tag">Game</span>
        <span class="tag">iPhone</span>
        <span class="tag">TV</span>
        <span class="tag">Asus Laptops</span>
        <span class="tag">Macbook</span>
        <span class="tag">SSD</span>
        <span class="tag">Graphics Card</span>
        <span class="tag">Power Bank</span>
        <span class="tag">Smart TV</span>
        <span class="tag">Speaker</span>
        <span class="tag">Tablet</span>
        <span class="tag">Microwave</span>
        <span class="tag">Samsung</span>
      </div>
    </div>
    <div class="footer_end"> All Rights Reserved YooStore.com © 2024. </div>
  </footer>
</div>