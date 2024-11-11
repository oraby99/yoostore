<?php

namespace App\Livewire\Auth;

use App\Mail\SendVerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Signup extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $agreeToTerms = false;
    public $verificationCode;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'agreeToTerms' => 'accepted',
    ];




    public function signup()
    {
        $this->validate();


        $this->verificationCode = random_int(1111, 9999);
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Mail::to($this->email)->send(new SendVerificationCode($this->verificationCode));
        session()->flash('message', 'Registration successful!');

        // dd($this->email);
        redirect('/verifyemail');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.auth.signup');
    }
}
