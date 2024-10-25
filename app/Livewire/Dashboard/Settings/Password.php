<?php

namespace App\Livewire\Dashboard\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Password extends Component
{

    public $currentPassword;
    public $newPassword;
    public $confirmNewPassword;

    protected $rules = [
        'currentPassword' => 'required',
        'newPassword' => 'required|min:6',
        'confirmNewPassword' => 'required|same:newPassword',
    ];

    public function saveChanges()
    {
        $this->validate();

        if (!Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Current password does not match.');
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->newPassword),
        ]);

        session()->flash('success', 'Password updated successfully.');
        $this->reset(['currentPassword', 'newPassword', 'confirmNewPassword']);
    }
    public function render()
    {
        return view('livewire.dashboard.settings.password');
    }
}
