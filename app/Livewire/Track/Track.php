<?php

namespace App\Livewire\Track;

use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Track extends Component
{

    public $orderId;

    public $billingEmail;

    public function trackOrder()
    {

        $this->validate([
            'orderId' => 'required|integer|exists:orders,id', 
        ]);

        $order = Order::where('id', $this->orderId)
        ->first();
        // dd($order);

        if ($order) {
            return redirect()->route('orderDetails', ['id' => $this->orderId]);
        } else {
            Session::flash('error', 'No such order found with the provided ID and email.');
        }
    }
    public function render()
    {
        return view('livewire.track.track');
    }
}
