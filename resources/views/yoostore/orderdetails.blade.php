@extends('yoostore.layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('yoostore/css/orderdetails.css') }}" />
@endsection
@section('content')

<div class="main">
    <div class="order-card">
        <div class="order-header">
            <h2>#96459761</h2>
            <span class="order-price">230 KWD</span>
        </div>


        <div class="order-progress">
            <div class="progress-step completed">
                <div class="progress-circle">✔</div>
                <div class="progress-icon">🛍️</div>
                <div class="progress-text">Order Placed</div>
            </div>
            <div class="progress-step completed">
                <div class="progress-circle">✔</div>
                <div class="progress-icon">📦</div>
                <div class="progress-text">Packaging</div>
            </div>
            <div class="progress-step">
                <div class="progress-circle">⏳</div>
                <div class="progress-icon">🚚</div>
                <div class="progress-text">Shipping</div>
            </div>
            <div class="progress-step">
                <div class="progress-circle">⏳</div>
                <div class="progress-icon">📬</div>
                <div class="progress-text">Delivered</div>
            </div>
        </div>


        <div class="order-activity">
            <div class="order-activity-item">
                <div class="order-activity-icon" style="   background-color: #0099cc;">✔</div>
                <div class="order-activity-content">
                    <p>Your order has been delivered. Thank you for shopping at YooStore!</p>
                    <span class="date">23 Jan, 2024 at 7:32 PM</span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">🛵</div>
                <div class="order-activity-content">
                    <p>Our delivery man (John Wick) has picked up your order for delivery.</p>
                    <span class="date">23 Jan, 2024 at 2:00 PM</span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">📦</div>
                <div class="order-activity-content">
                    <p>Your order has reached the last mile hub.</p>
                    <span class="date">22 Jan, 2024 at 8:00 AM</span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">🚚</div>
                <div class="order-activity-content">
                    <p>Your order is on the way to (last mile) hub.</p>
                    <span class="date">21 Jan, 2024 at 5:32 AM</span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">🔍</div>
                <div class="order-activity-content">
                    <p>Your order is successfully verified.</p>
                    <span class="date">20 Jan, 2024 at 7:32 PM</span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">✅</div>
                <div class="order-activity-content">
                    <p>Your order has been confirmed.</p>
                    <span class="date">19 Jan, 2024 at 2:61 PM</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection