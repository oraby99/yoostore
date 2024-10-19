@extends('yoostore.layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('yoostore/css/track.css') }}" /> 
@endsection
@section('content')
<div class="d-flex justify-content-center align-items-center main">
      <div class="div  w-75 p-5">
        <p style="font-size: 32; font-weight: 600;">Track Order</p>
        <p style="color: #5F6C72; width: 760px; font-weight: 500;">
          To track your order please enter your order ID in the input field
          below and press the “Track Order” button. this was given to you on
          your receipt and in the confirmation email you should have received.
        </p>
        <div class="d-flex justify-content-left inputs ">

          <div style="margin-right: 50px; " class="">
            <p>Order ID</p>
            <input type="text" placeholder="ID.." />
          </div>

          <div>
            <p>Billing Email</p>
            <input type="text" placeholder="Email Adrress" />
          </div>
          
        </div>
        <p class="my-3" style="font-size: 20px;"><i class="fa-solid fa-circle-info" style="color: grey;"></i> We'll never share your email with anyone else</p>
        <button>Track Order <I><i class="fa-solid fa-arrow-right-long mx-3"></i></I></button>
      </div>
    </div>

@endsection