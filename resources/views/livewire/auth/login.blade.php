<div class="main">
    <div class="signup">
        <div class="header d-flex justify-content-around align-items-center">
            <a href="{{ route('login') }}" class="active">Login</a>
            <a href="{{ route('signup') }}" >Sign Up</a>
        </div>
        <hr style="position: relative; top: -23px;">

        <!-- Email Field -->
        <div class="inputs" style="position: relative; top: 0px;">
            <p>Email Address</p>
            <input type="text" placeholder="Email Address" wire:model="email">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <!-- Password Field -->
        <div class="inputs" style="position: relative; top: -20px;">
            <div class="d-flex justify-content-between">
                <p>Password</p>
                <a href="" style="text-decoration: none;">Forget password?</a>
            </div>
            <input type="password" placeholder="Password" class="password" wire:model="password">
            @error('password') <span class="error">password not correct</span> @enderror
        </div>



        <div class="d-flex justify-content-center" style="position: relative; top: -40px;">
            @if (session()->has('message'))
            <p>{{ session('message') }}</p>
            @endif

            @if (session()->has('error'))
            <p>{{ session('error') }}</p>
            @endif
        </div>
        <!-- Sign-in Button -->
        <div class="d-flex justify-content-center" style="position: relative; top: -20px;">
            <button wire:click="login">Sign in <i class="fa-solid fa-arrow-right-long mx-3"></i></button>
        </div>


    </div>
</div>