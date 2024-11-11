<?php

namespace App\Livewire\Auth;

use App\Mail\SendVerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Verifyemail extends Component
{

    public $verificationCode;
    public $email;
    public $user;

    public function mount($email = null)
    {
        $this->email = $email;

        if ($this->email) {
            $this->user = User::where('email', $this->email)->firstOrFail();
        }
    }


    public function verifyCode()
    {
        if ($this->user->verification_code == $this->verificationCode) {
            $this->user->update(['email_verified_at' => now()]);
            session()->flash('message', 'Your email has been verified!');
            return redirect()->route('login');  // Redirect to your desired page
        } else {
            session()->flash('error', 'Invalid verification code');
        }
    }

    public function resendCode()
    {
        $newCode = random_int(1111, 9999);
        $this->user->update(['verification_code' => $newCode, 'email_verified_at' => null]);

        try {
            Mail::to($this->user->email)->send(new SendVerificationCode($newCode));
            session()->flash('message', 'Verification code sent');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send the verification code');
        }
    }
    public function render()
    {
        return view('livewire.auth.verifyemail');
    }
}
