

<div class="main">
    <div class="signup">
        <div class="header d-flex justify-content-around align-items-center">
            <a href="{{route('login')}}"">Login</a>
            <a href="{{route('signup')}}" class="active">Sign Up</a>
        </div>
        <hr style="position: relative; top: -23px;">

        <!-- Name Field -->
        <div class="inputs">
            <p>Name</p>
            <input type="text" placeholder="Name" wire:model="name">
            @error('name') <span class="badge text-bg-danger w-25">{{ $message }}</span> @enderror
        </div>

        <!-- Email Field -->
        <div class="inputs" style="position: relative; top: -70px;">
            <p>Email Address</p>
            <input type="text" placeholder="Email Address" wire:model="email">
            @error('email') <span class="badge text-bg-danger w-50">this email already exist</span> @enderror
        </div>

        <!-- Password Field -->
        <div class="inputs" style="position: relative; top: -100px;">
            <p>Password</p>
            <input type="password" placeholder="Password" class="password" wire:model="password">
            @error('password') <span class="badge text-bg-danger w-50">password must be 6 char min</span> @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="inputs" style="position: relative; top: -130px;">
            <p>Confirm Password</p>
            <input type="password" placeholder="Confirm Password" wire:model="password_confirmation">
        </div> 

        <!-- Terms of Service Checkbox -->
        <div class="mx-3" style="position: relative; top: -150px;">
            <p><input type="checkbox" wire:model="agreeToTerms"> Are you agree to Clicon Terms of Condition and Privacy Policy. </p> <br>
            @error('agreeToTerms') <span class="badge text-bg-danger w-25 mx-3" style="position: relative; top: -30px">you should agree on terms</span> @enderror
        </div>

        <!-- Sign Up Button -->
        <div class="d-flex justify-content-center">
            <button wire:click="signup">Sign Up</button>
        </div>
        @if (session()->has('message'))
        <div class="d-flex justify-content-center" style="position: relative; top: -120px">
            <p>Regiseteration success</p>
        </div>
        
        @endif

    </div>
</div>
