@extends('yoostore.layout.master')
@section('css')
<link rel="stylesheet" href="{{asset('yoostore/css/cart.css') }} "/>
@endsection
@section('content')


<div class="cart-container">
 

<livewire:cart.cart-component>
    </div>




@endsection