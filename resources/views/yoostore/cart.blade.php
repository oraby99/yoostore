@extends('yoostore.layout.master')
@section('css')
<link rel="stylesheet" href="{{asset('yoostore/css/cart.css') }} "/>
@endsection
@section('content')


<div class="cart-container">
    <div class="main">
        <div class="shopping-cart">
            <h2>Shopping Card</h2>
            <table>
                <thead>
                    <tr style="background-color: #E4E7E9;">
                        <th>Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>SubTotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <i class="fa-regular fa-circle-xmark text-danger"></i>                            <img src="{{ asset('yoostore/images/xaomi.png') }}" alt="Gaming Headphones">
                            <span>Wired Over-Ear Gaming Headphones with USB</span>
                        </td>
                        <td>20 KWD</td>
                        <td class="quantity">
                            <button>-</button>
                            <span>3</span>
                            <button>+</button>
                        </td>
                        <td>60 KWD</td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-buttons">
                <button class="return-button"><i class="fa-solid fa-arrow-left mx-3"></i>Return to Shop</button>
                <button class="update-button">Update Cart <i class="fa-solid fa-arrow-right mx-3"></i></button>
            </div>
        </div>
        <div class="cart-summary">
            <div class="cart-totals">
                <h3>Card Totals</h3>
                <div class="totals-row">
                    <span>Sub-total</span>
                    <span>310 KWD</span>
                </div>
                <div class="totals-row">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>
                <div class="totals-row">
                    <span>Discount</span>
                    <span>20 KWD</span>
                </div>
                <div class="totals-row">
                    <span>Tax</span>
                    <span>290 KWD</span>
                </div>
                <hr>
                <div class="totals-row total">
                    <span>Total</span>
                    <span>290 KWD</span>
                </div>
                <button class="checkout-button">Proceed to Checkout</button>
            </div>
        </div>
    </div>
    </div>




@endsection