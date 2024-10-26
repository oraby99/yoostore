<?php

namespace App\Livewire\Checkout;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Checkout extends Component
{

    public $addresses;
    public $defaultAddressId;




    public function mount()
    {
        $this->loadAddresses();
    }






    public function loadAddresses()
    {
        $this->addresses = Address::where('user_id', Auth::id())->get();
        $this->defaultAddressId = $this->addresses->firstWhere('is_default', 1)->id ?? null;
    }

    public function setDefault($addressId)
    {
        Address::where('user_id', Auth::id())->update(['is_default' => 0]); // Reset all to non-default
        Address::where('id', $addressId)->update(['is_default' => 1]);      // Set selected as default

        $this->defaultAddressId = $addressId; // Update the selected default address
        $this->loadAddresses(); // Reload addresses to reflect changes
    }
    public function render()
    {
        return view('livewire.checkout.checkout');
    }
}
