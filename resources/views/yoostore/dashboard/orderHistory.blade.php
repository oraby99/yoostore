@extends('yoostore.layout.master')
@section('css')
<link rel="stylesheet" href="{{ asset('yoostore/css/orderHistory.css') }}" />
@endsection

@section('content')

@php
$user = Auth::user();
$orders = $user->orders()->paginate(10); 
@endphp


<div class="container-fluid">
    <div class="row d-flex align-items-start">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar" style="height: auto;">
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
        <div class="col-md-8">
            <div class="row">
                <!-- Account Settings -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 order-history">
                            <h2 style="font-size: 18px;">Order History</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Order ID</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    @php
                                    $latestStatusChange = $order->statusChanges()->latest()->first();
                                    @endphp
                                    <tr>
                                        <td class="text-center">#{{ $order->id }}</td>
                                        @if ($latestStatusChange)
                                        @if ($latestStatusChange == 'Pending')
                                        <td class="order-status in-progress text-center">{{ $latestStatusChange->status }}</td>
                                        @elseif($latestStatusChange == 'Received')
                                        <td class="order-status completed text-center">{{ $latestStatusChange->status }}</td>
                                        @elseif($latestStatusChange == 'Cancelled')
                                        <td class="order-status canceled text-center">{{ $latestStatusChange->status }}</td>
                                        @else
                                        <td class="order-status completed text-center">{{ $latestStatusChange->status }}</td>
                                        @endif
                                        @else
                                        <td class="order-status text-center">No Status</td>
                                        @endif
                                        <td class="text-center">{{ $order->created_at->format('d M, Y') }}</td>
                                        <td class="text-center">{{ $order->total_price }} KWD</td>
                                        <td class="text-center">
                                            <a href="{{ route('orderDetails', $order->id) }}">View Details <i class="fa-solid fa-arrow-right"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <nav>
                                {{ $orders->links() }} 
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection