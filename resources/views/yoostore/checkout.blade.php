@extends('yoostore.layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('yoostore/css/checkout.css') }}" />
@endsection
@section('content')
<div class="container">

<livewire:checkout.checkout>
</div>


@endsection