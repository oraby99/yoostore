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
            <input type="text" placeholder="ID.." wire:model="orderId" />
            @error('orderId') <span class="text-danger">No such an order id</span> @enderror
        </div>

        <div>
            <p>Billing Email</p>
            <input type="text" placeholder="Email Adrress" />
        </div>

    </div>
    <p class="my-3" style="font-size: 20px;"><i class="fa-solid fa-circle-info" style="color: grey;"></i> We'll never share your email with anyone else</p>
    <button wire:click="trackOrder">Track Order <I><i class="fa-solid fa-arrow-right-long mx-3"></i></I></button>
    @if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif
</div>