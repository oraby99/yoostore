@extends('yoostore.layout.master')
@section('css')
<link rel="stylesheet" href="{{asset('yoostore/css/setting.css') }}" />
@endsection
@section('content')


<div class="container-fluid">
    <div class="row d-flex align-items-start" style="padding: 0 150px;">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <a href="#" class="active link"><i class="fa-solid fa-layer-group"></i>Dashboard</a>
            <a href="{{ route('orderHistory') }}" class="link"><i class="fa-solid fa-shop"></i>Order History</a>
            <a href="{{ route('track') }}" class="link"><i class="fa-solid fa-location-dot"></i>Track Order</a>
            <a href="{{ route('cart') }}" class="link"><i class="fa-solid fa-cart-shopping"></i>Shopping Cart</a>
            <a href="{{ route('wishlist') }}" class="link"><i class="fa-regular fa-heart"></i>Wishlist</a>
            <a href="{{ route('browsingHistory') }}" class="link"><i class="fa-solid fa-clock-rotate-left"></i>Browsing History</a>
            <a href="{{ route('settings') }}" class="link"><i class="fa-solid fa-gear"></i>Settings</a>
            <livewire:dashboard.logout>
        </div>

        <!-- Content -->
        <div class="col-md-9">
            <div class="row">
                <!-- Account Settings -->
                <div class="col-md-12">
                   
                <livewire:dashboard.settings.personalinfo>
                </div>

            
                    <livewire:dashboard.settings.addresses>
                        
                        <!-- Password -->
                        
                        <livewire:dashboard.settings.password>

            </div>
        </div>
    </div>
</div>


@endsection