<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderdetailController extends Controller
{
    public function index()
    {
        return view('yoostore.orderdetails');
    }
}
