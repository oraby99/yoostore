@extends('yoostore.layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('yoostore/css/checkout.css') }}" />
@endsection
@section('content')
<div class="container">

<div class="checkout-container">
    <form class="billing-info">
        <h2>Billing Information</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="first-name">User name</label>
                <input type="text" id="first-name" placeholder="First name">
            </div>
            <div class="form-group">
                <label for="company-name">Last name</label>
                <input type="text" id="last-name" placeholder="Last name">
            </div>
            <div class="form-group">
                <label for="company-name">Company Name (Optional)</label>
                <input type="text" id="company-name" placeholder="Company name">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" placeholder="Address">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="country">Country</label>
                <div class="custom-select">
                    <select id="country">
                        <option>Select...</option>
                        <option>USA</option>
                        <option>Canada</option>
                        <option>UK</option>
                    </select>
                    <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="country">Country</label>
                <div class="custom-select">
                    <select id="country">
                        <option>Select...</option>
                        <option>USA</option>
                        <option>Canada</option>
                        <option>UK</option>
                    </select>
                    <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <div class="custom-select">
                    <select id="country">
                        <option>Select...</option>
                        <option>USA</option>
                        <option>Canada</option>
                        <option>UK</option>
                    </select>
                    <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="form-group">
                <label for="zip">Zip Code</label>
                <input type="text" id="zip" placeholder="Zip Code">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" placeholder="Phone Number">
            </div>
        </div>

        <div class="form-group">
            <input type="checkbox" id="different-address">
            <label for="different-address">Ship to a different address</label>
        </div>

        <h3>Payment Option</h3>
        <div class="payment-options">
            <label>
                <input type="radio" name="payment" checked>
                Cash on Delivery
            </label>
            <label>
                <input type="radio" name="payment">
                <img src="{{ asset('yoostore/images/master card.png') }}" alt="Venmo">
                Master Card
            </label>
            <label>
                <input type="radio" name="payment">
                <img src="{{ asset('yoostore/images/amrican express.png') }}" alt="Venmo">
                Amrican Express
            </label>
            <label>
                <input type="radio" name="payment">
                <img src="{{ asset('yoostore/images/paypall.png') }}" alt="Paypal">
            </label>
            <label>
                <input type="radio" name="payment">
                <img src="{{ asset('yoostore/images/visa.png') }}" alt="Amazon Pay">
            </label>
        
        </div>

        <div class="card-details">
            <div class="form-row">
                <div class="form-group">
                    <label for="card-name">Name on Card</label>
                    <input type="text" id="card-name" placeholder="Name on Card">
                </div>
                <div class="form-group">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" placeholder="Card Number">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="expiry">Expire Date</label>
                    <input type="text" id="expiry" placeholder="DD/YY">
                </div>
                <div class="form-group">
                    <label for="cvc">CVC</label>
                    <input type="text" id="cvc" placeholder="CVC">
                </div>
            </div>
        </div>

        <h3>Additional Information</h3>
        <div class="form-group">
            <label for="order-notes">Order Notes (Optional)</label>
            <textarea id="order-notes" placeholder="Notes about your order, e.g., special notes for delivery"></textarea>
        </div>
    </form>

    <div class="order-summary">
        <h3>Order Summary</h3>
        <div class="summary-content">
            <img src="{{ asset('yoostore/images/headphone1.jpeg') }}" alt="">
            <p>Wired Over-Ear Gaming Headphones with USB</p>
            <p style="font-weight: 600;">3 x <span class="text-info" >20 KWD</span></p>
            <hr>
            <div>
                <p class="d-flex justify-content-between" style="font-weight: 400; color: grey;">Sub total: <span style="font-weight: 600; color: black;">20 KWD</span></p>
                <p class="d-flex justify-content-between" style="font-weight: 400; color: grey;">shiping: <span style="font-weight: 600; color: black;">20 KWD</span></p>
                <p class="d-flex justify-content-between" style="font-weight: 400; color: grey;">Discount: <span style="font-weight: 600; color: black;">20 KWD</span></p>
                <p>Tax: 0</p>
            </div>
            <p class="total">Total: 290 KWD</p>
            <button>Place Order</button>
        </div>
    </div>
</div>
</div>


@endsection