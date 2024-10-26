<?php

namespace App\Livewire\Dashboard\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Personalinfo extends Component
{
    public $name;
    public $email;
    public $phone;

    public function mount()
    {
        // Load the current user's information into the component properties
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function savePersonalInfo()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone' => 'nullable|string|max:15',
        ]);

        // Update user information
        User::where('id', auth()->user()->id)->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('message', 'Personal information saved successfully!');

        // Reset the properties if needed
        $this->reset(['name', 'email', 'phone']);
    }

    public function render()
    {
        return view('livewire.dashboard.settings.personalinfo');
    }
}
