<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderdetailController extends Controller
{
    public function index($id)
    {
        $order = Order::where('id', $id)->with('orderproducts')->first();
        return view('yoostore.orderdetails' , [
            'order' => $order,
        ]);
    }
}
