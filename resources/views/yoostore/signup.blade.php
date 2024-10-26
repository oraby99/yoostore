@extends('yoostore.layout.masterAuth')
@section('css')
<!-- <link href="{{ asset('yoostore/css/auth/login.css')}}" rel="stylesheet">
<link href="{{ asset('yoostore/css/auth/resetPassword.css')}}" rel="stylesheet">
<link href="{{ asset('yoostore/css/auth/forgetPassword.css')}}" rel="stylesheet">
<link href="{{ asset('yoostore/css/auth/verifyRmail.css')}}" rel="stylesheet"> -->
<link href="{{ asset('yoostore/css/auth/signup.css')}}" rel="stylesheet">
@endsection
@section('content')

<livewire:auth.signup>


@endsection