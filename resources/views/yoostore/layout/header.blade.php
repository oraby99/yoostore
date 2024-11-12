
@php
$categories = App\Models\Category::with('subcategories')->get();
@endphp

<nav class="navbarr">
        <!-- First Row -->
        <div class="navbarr-head navbarr-row">
            <div class="logo">welcome to Yoostore</div>
            <div class="social-media">
                <div class="social-media-icons">
                    <div>follow us :</div>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
                        </div>
        </div>

        <!-- Second Row -->
        <div class="navbarr-row">
           <a href="{{ route('index') }}"><img src="{{ asset('yoostore/images/yoostoree.png') }}" alt="" width="200"></a>
            <div class="search-bar d-none">
                <input type="text" placeholder="Search for anything..." />
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div class="shopping-icons">
                <a href="{{ route('cart') }}"><i class="fa-solid fa-cart-shopping"></i></a>
                <a href="{{ route('wishlist') }}"><i class="fa-regular fa-heart"></i></a>
                <a href="{{ route('signup') }}"><i class="fa-regular fa-user"></i></a>
                <livewire:auth.logout>
            </div>
        </div>

        <!-- Third Row -->
        <div class="navbarr-row">
            <div class="options">
                <div class="categories">
                    <select>
                    @foreach($categories as $category)
                    @foreach($category->subcategories as $subcategory)
                    <option value="electronics">{{ $subcategory->name }}</option>
                    @endforeach
                    @endforeach
                        
                    </select>
                </div>
                <a href="{{ route('track') }}"><i class="fa-solid fa-location-dot"></i> Track Order</a>
                <a href="{{ route('cart') }}" id="a1">Cart</a>
                <a href="{{ route('settings') }}" id="a2">Dashboard</a>
                <a href="{{ route('faq') }}" id="a2">Faq</a>
            </div>
        </div>
        </div>
    </nav>


