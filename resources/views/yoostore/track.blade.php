@extends('yoostore.layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('yoostore/css/track.css') }}" /> 
@endsection
@section('content')
<div class="d-flex justify-content-center align-items-center main">
    <livewire:track.track>
    </div>

@endsection