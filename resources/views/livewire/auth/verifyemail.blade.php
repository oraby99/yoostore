<div class="main">
    <div class="signup p-5">
        <div>
            <p class="text-center" style="font-size: 22px; font-weight: 600;">Verify Your Email Address</p>
            <p class="text-center text-muted">Please enter the verification code sent to your email.</p>
        </div>
        
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @elseif (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="inputs" style="position: relative; top: 0px;">
            <div class="d-flex justify-content-between">
                <p>Verification code</p>
                <p><a href="#" wire:click.prevent="resendCode">Resend code</a></p>
            </div>
            <input type="text" placeholder="code" wire:model="verificationCode">
        </div>

        <div class="d-flex justify-content-center" style="position: relative; top: 0px;">
            <button wire:click="verifyCode">Verify Email <i class="fa-solid fa-arrow-right-long mx-3"></i></button>
        </div>
    </div>
</div>
