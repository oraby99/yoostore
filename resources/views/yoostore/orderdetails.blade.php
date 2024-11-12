@extends('yoostore.layout.master')
@section('css')
<link rel="stylesheet" href="{{asset('yoostore/css/orderdetails.css') }}" />
@endsection
@section('content')

<div class="main">
    <div class="order-card">
        <div class="order-header">
            <h2>#{{$order->id}}</h2>
            <span class="order-price">${{$order->total_price }} KWD</span>
        </div>


        @if ($orderstatus->name == 'Pending')

        <div class="order-progress">
            <div class="progress-step completed">
                <div class="progress-circle">✔</div>
                <div class="progress-icon">🛍️</div>
                <div class="progress-text">Pending</div>
            </div>
            <div class="progress-step completed">
                <div class="progress-circle">⏳</div>
                <div class="progress-icon">✅</div>
                <div class="progress-text">Recievd</div>
            </div>
            <div class="progress-step">
                <div class="progress-circle">⏳</div>
                <div class="progress-icon">🚚</div>
                <div class="progress-text">Delivered</div>
            </div>
        </div>
        @elseif($orderstatus->name == 'received ')
        <div class="order-progress">
            <div class="progress-step completed">
                <div class="progress-circle">✔</div>
                <div class="progress-icon">🛍️</div>
                <div class="progress-text">Pending</div>
            </div>
            <div class="progress-step completed">
                <div class="progress-circle">✔</div>
                <div class="progress-icon">✅</div>
                <div class="progress-text">Recievd</div>
            </div>
            <div class="progress-step">
                <div class="progress-circle">⏳</div>
                <div class="progress-icon">🚚</div>
                <div class="progress-text">Delivered</div>
            </div>
        </div>
        @elseif($orderstatus->name == 'Cancelled')
        <div class="order-progress">
            <div class="progress-step completed">

                <div class="">❌</div>
                <div class="progress-icon">🛍️</div>
                <div class="progress-text">Cancelled</div>
            </div>
            <div class="progress-step completed">
                <div class="">❌</div>
                <div class="progress-icon">📦</div>
                <div class="progress-text">Pending</div>
            </div>
            <div class="progress-step">
                <div class="">❌</div>
                <div class="progress-icon">✅</div>
                <div class="progress-text">Recievd</div>
            </div>
            <div class="progress-step">
                <div class="progress-circle">❌</div>
                <div class="progress-icon">🚚</div>
                <div class="progress-text">Delivered</div>
            </div>
        </div>
        @elseif ($orderstatus->name == 'Delivered')
        <div class="order-progress">
            <div class="progress-step completed">
                <div class=" progress-circle"" >✔</div>
                <div class=" progress-icon">📦</div>
                <div class="progress-text">Pending</div>
            </div>
            <div class="progress-step completed">
                <div class=" progress-circle"" >✔</div>
                <div class=" progress-icon">✅</div>
                <div class="progress-text">Recievd</div>
            </div>
            <div class="progress-step completed">
                <div class="progress-circle">✔</div>
                <div class="progress-icon">🚚</div>
                <div class="progress-text">Delivered</div>
            </div>
        </div>
        @endif


        @if ($orderstatus->name == 'Cancelled')
        <div class="order-activity">
            <div class="order-activity-item">
                <div class="order-activity-icon">❌</div>
                <div class="order-activity-content">
                    <p>Your order has been cancelled.</p>
                    <span class="date"></span>
                </div>
            </div>
        </div>
        @elseif ( $orderstatus->name == 'Pending')
        <div class="order-activity">
            <div class="order-activity-item">
                <div class="order-activity-icon">📦</div>
                <div class="order-activity-content">
                    <p>Your order has reached the last mile hub.</p>
                    <span class="date"></span>
                </div>
            </div>
        </div>
        @elseif ($orderstatus->name == 'received ')
        <div class="order-activity">
            <div class="order-activity-item">
                <div class="order-activity-icon">📦</div>
                <div class="order-activity-content">
                    <p>Your order has reached the last mile hub.</p>
                    <span class="date"></span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">✅</div>
                <div class="order-activity-content">
                    <p>Your order has been confirmed.</p>
                    <span class="date"></span>
                </div>
            </div>
        </div>
        @elseif ($orderstatus->name == 'Delivered')
        <div class="order-activity">
            <div class="order-activity-item">
                <div class="order-activity-icon">📦</div>
                <div class="order-activity-content">
                    <p>Your order has reached the last mile hub.</p>
                    <span class="date"></span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">✅</div>
                <div class="order-activity-content">
                    <p>Your order is on the way to (last mile) hub.</p>
                    <span class="date"></span>
                </div>
            </div>
            <div class="order-activity-item">
                <div class="order-activity-icon">🚚</div>
                <div class="order-activity-content">
                    <p>Your order has been delivered.</p>
                    <span class="date"></span>
                </div>
            </div>
        </div>
        @endif


        @if($orderstatus->name == 'Pending' || $orderstatus->name == 'received ')

        <form action="{{ route('order.cancel', ['id' => $order->id]) }}" method="POST">
            @csrf
            <div>
                <button type="submit" class="btn btn-danger w-100">Cancel Order</button>
            </div>
        </form>
        @endif
    </div>


</div>

@endsection