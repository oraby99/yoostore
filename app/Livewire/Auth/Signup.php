<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Signup extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $agreeToTerms = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'agreeToTerms' => 'accepted',
    ];



    public function signup()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('message', 'Registration successful!');

        redirect('/login');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.auth.signup');
    }
}
