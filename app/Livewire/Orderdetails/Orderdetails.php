<?php

namespace App\Livewire\Orderdetails;

use App\Models\Order;
use App\Models\OrderStatus;
use Livewire\Component;

class Orderdetails extends Component
{

    public $orderid;

    public function mount($id)
    {
        $this->orderid = $id;
    }
    public function cancel ()
    {
        //get the id from the url 
       
        dd($this->orderid);
        $order = Order::find($this->orderid);
        $order->order_status_id = OrderStatus::where('name', 'Cancelled')->first()->id;
        $order->save();
        return redirect()->route('orderDetails', ['id' => $this->orderid]);
    }
    public function render()
    {
        return view('livewire.orderdetails.orderdetails');
    }
}
