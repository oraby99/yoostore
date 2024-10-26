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
                @foreach ($products as $product) 
                <tr>
                <td>
                    <i class="fa-regular fa-circle-xmark text-danger" wire:click="removeProduct({{ $product->id }})" style="cursor:pointer;"></i> 
                    <img src="{{ asset('yoostore/images/xaomi.png') }}" alt="{{ $product->name }}">
                    <span>{{ $product->name }}</span>
                </td>
                <td>{{ $product->productDetail->typeprice }} KWD</td>
                <td class="quantity">
                    <button wire:click="decrementQuantity({{ $product->id }})">-</button>
                    <span>{{ $quantities[$product->id] }}</span>
                    <button wire:click="incrementQuantity({{ $product->id }})">+</button>
                </td>
                <td>{{ $product->productDetail->typeprice * $quantities[$product->id] }} KWD</td>
            </tr>
                @endforeach
              
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
                <span>{{ $total }} KWD</span>
            </div>
            <div class="totals-row">
                <span>Shipping</span>
                <span>Free</span>
            </div>
            <div class="totals-row d-none">
                <span>Discount</span>
                <span>{{ $discount }} KWD</span>
            </div>
            <hr>
            <div class="totals-row total">
                <span>Total</span>
                <span>{{ $total}} KWD</span>
            </div>
            <button class="checkout-button" wire:click="checkout()">Proceed to Checkout</button>
        </div>
    </div>
</div>