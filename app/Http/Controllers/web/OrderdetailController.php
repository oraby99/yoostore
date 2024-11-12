<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderdetailController extends Controller
{

 
    public function index($id)
    {
        $order = Order::where('id', $id)->with('orderproducts')->first();
        $orderstatus = OrderStatus::where('id', $order->order_status_id)->first();
        // dd($orderstatus);
        return view('yoostore.orderdetails' , [
            'order' => $order,
            'orderstatus' => $orderstatus
        ]);
    }



    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->order_status_id = OrderStatus::where('name', 'Cancelled')->first()->id;
            $order->save();
        }

        return redirect()->route('orderDetails', ['id' => $id])->with('status', 'Order cancelled successfully.');
    }
}
