<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{


    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            session()->flash('message', 'Login successful. Token: ' . $token);
            return redirect()->route('index');
        } else {
            session()->flash('error', 'Invalid credentials');
        }
    }
    public function render()
    {
        return view('livewire.auth.login');
    }
}
