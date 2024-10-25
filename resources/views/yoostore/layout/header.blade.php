
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
                
                <div class="navbarr-select">
                    <select>
                        <option value="en">English</option>
                        <option value="es">Spanish</option>
                        <option value="fr">French</option>
                    </select>
                </div>
                <div class="navbarr-select">
                    <select>
                        <option value="en">Egpty</option>
                        <option value="es">kwd</option>
                        <option value="fr">French</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="navbarr-row">
           <a href="{{ route('index') }}"><img src="{{ asset('yoostore/images/yoostoree.png') }}" alt="" width="200"></a>
            <div class="search-bar">
                <input type="text" placeholder="Search for anything..." />
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div class="shopping-icons">
                <a href="{{ route('cart') }}"><i class="fa-solid fa-cart-shopping"></i></a>
                <a href=""><i class="fa-regular fa-heart"></i></a>
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
                <a href="" id="a1">Summer Sale</a>
                <a href="#">New Arrival</a>
                <a href="#" id="a2">Best Sellers</a>
            </div>
            <div class="phone">
                <div class="whatsapp"></div>
                +2342834942
            </div>
        </div>
        </div>
    </nav>


