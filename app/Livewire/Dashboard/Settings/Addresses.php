<?php

namespace App\Livewire\Dashboard\Settings;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Addresses extends Component
{

    public $homeName, $homePhone, $homeStreet, $homeLandmark, $homeArea, $homeCity , $homeFlat , $homeCountry;

    public $officeName, $officePhone, $officeStreet, $officeLandmark, $officeArea, $officeCity , $officeFlat , $officeCountry;

    public $addresses;
    public $defaultAddressId;




    public function mount()
    {
        $this->loadAddresses();
    }


    public function saveHomeAddress()
    {
        $this->validate([
            'homeName' => 'required|string',
            'homePhone' => 'required|string',
            'homeStreet' => 'required|string',
            'homeLandmark' => 'nullable|string',
            'homeArea' => 'required|string',
            'homeCity' => 'required|string',
            'homeFlat' => 'required|string',
            'homeCountry' => 'required|string',
        ]);
        
        Address::create([
            'user_id' => Auth::id(),
            'name' => $this->homeName,
            'phone' => $this->homePhone,
            'street' => $this->homeStreet,
            'landmark' => $this->homeLandmark,
            'area' => $this->homeArea,
            'city' => $this->homeCity,
            'flat_no' => $this->homeFlat,
            'country' => $this->homeCountry,
            'address_type' => 'home',
            'is_default' => 0,
        ]);
        $this->reset(['homeName', 'homePhone', 'homeStreet', 'homeLandmark', 'homeArea', 'homeCity', 'homeFlat', 'homeCountry']);
        session()->flash('success1', 'Home address saved successfully!');
    }
    
    
    public function saveOfficeAddress()
    {
        $this->validate([
            'officeName' => 'required|string|max:255',
            'officePhone' => 'required|string|max:255',
            'officeStreet' => 'required|string|max:255',
            'officeFlat' => 'required|string',
            'officeCountry' => 'required|string',
            'officeArea' => 'required|string|max:255',
            'officeCity' => 'required|string|max:255',
        ]);
        
        Address::create([
            'user_id' => Auth::id(),
            'name' => $this->officeName,
            'phone' => $this->officePhone,
            'street' => $this->officeStreet,
            'landmark' => $this->officeLandmark,
            'country' => $this->officeCountry,
            'area' => $this->officeArea,
            'city' => $this->officeCity,
            'flat_no' => $this->officeFlat,
            'address_type' => 'office',
            'is_default' => 0,
        ]);


        $this->reset(['officeName', 'officePhone', 'officeStreet', 'officeLandmark', 'officeArea', 'officeCity', 'officeFlat', 'officeCountry']);

        session()->flash('success2', 'Office address saved successfully!');
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

    public function deleteAddress($id)
    {
        Address::where('id', $id)->delete();
        $this->loadAddresses();
        session()->flash('message', 'Address deleted successfully!');
    }
    public function render()
    {
        return view('livewire.dashboard.settings.addresses');
    }
}
