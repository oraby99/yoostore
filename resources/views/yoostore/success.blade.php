@extends('yoostore.layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('yoostore/css/succes.css') }}" />
@endsection
@section('content')

<div class="container">
        <div class="d-flex justify-content-center flex-column align-items-center">
              <img src="{{asset('yoostore/images/success.png') }}" alt="" width="200px" height="200px">
              <p class="text-center" style="font-size: 28px; font-weight: 600">Your order is successfully place</p>
              <p style=" color: grey; font-weight: 500;" class="text-center">Pellentesque sed lectus nec tortor tristique accumsan quis dictum risus. Donec volutpat mollis nulla non facilisis.</p>
        </div>
        <div class="d-flex justify-content-center">
            <a class="leftbutton" style="margin-right: 6px;"><i class="fa-solid fa-layer-group " style="margin-right:6px;"></i>To Dashboard</ش>
            <a class="rightbutton" style="margin-left: 6px;"> View Order <i><i class="fa-solid fa-arrow-right-long "></i></i></a>
        </div>
    </div>

@endsection