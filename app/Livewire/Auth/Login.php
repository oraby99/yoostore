<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{


    public $email;
    public $password;
    public $remember = false; 

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $this->email)->first();

        if ($user && Hash::check($this->password, $user->password)) {
            $token = $user->createToken('authToken')->plainTextToken;
            Auth::login($user, $this->remember);

            session()->flash('message', 'Login successful. Token: ' . $token);
            return redirect()->route('index');
        } else {
            session()->flash('error', 'Invalid credentials');
        }
    }

    
    public function loginm()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $this->email)->first();

        if ($user && Hash::check($this->password, $user->password)) {
            $token = $user->createToken('authToken')->plainTextToken;
            Auth::login($user, $this->remember);

            if ($user->type === 'user') {
                return redirect()->route('home')->with('token', $token);
            } elseif ($user->type === 'designer') {
                return redirect()->route('orderListView')->with('token', $token);
            }
            elseif ($user->email === 'admin@admin.com') {
                return redirect()->route('productView')->with('token', $token);
            }
        } else {
            session()->flash('error', 'May be password or email is incorrect.');
        }
    }
    public function render()
    {
        return view('livewire.auth.login');
    }
}
