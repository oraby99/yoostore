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
            @if (Auth::user())
            <a href="{{ route('cart') }}"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="{{ route('wishlist') }}"><i class="fa-regular fa-heart"></i></a>
            <livewire:auth.logout>
            @else
                <a href="{{ route('signup') }}"><i class="fa-regular fa-user"></i></a>
            @endif
        </div>
    </div>

    <!-- Third Row -->
    <div class="navbarr-row">
        <div class="options">
            <div class="categories">
                <select id="subcategorySelect">
                    <option value="">Select a Subcategory</option> <!-- Optional default option -->
                    @foreach($categories as $category)
                    @foreach($category->subcategories as $subcategory)
                    <option value="{{ route('category', $subcategory->id) }}">{{ $subcategory->name }}</option>
                    @endforeach
                    @endforeach
                </select>
            </div>
            @if (Auth::user())
            <a href="{{ route('track') }}"><i class="fa-solid fa-location-dot"></i> Track Order</a>
            <a href="{{ route('cart') }}" id="a1">Cart</a>
            <a href="{{ route('settings') }}" id="a2">Dashboard</a>
            @else
            <a href="{{ route('faq') }}" id="a2">Faq</a>
            @endif
        </div>
    </div>
    </div>
</nav>

<script>
    document.getElementById('subcategorySelect').addEventListener('change', function() {
        var url = this.value;
        if (url) {
            window.location.href = url; // Redirect to the selected route
        }
    });
</script>