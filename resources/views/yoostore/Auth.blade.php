@extends('yoostore.layout.master')
@section('css')
    <link href="{{ asset('yoostore/css/auth/signup.css')}}" rel="stylesheet">
@endsection
@section('content')

<div class="main">
        <div class="signup">
            <div class="header d-flex justify-content-around align-items-center">
                <span >Login</span>
                <span class="active">Sign Up</span>
            </div>
            <hr style="position: relative; top: -23px;">

            <div class="inputs">
                <p>Name</p>
                <input type="text" placeholder="Name">
            </div>
            <div class="inputs" style="position: relative; top: -70px;">
                <p>Email Address</p>
                <input type="text" placeholder="Email Address">
            </div>
            <div class="inputs" style="position: relative; top: -100px;">
                <p>Password</p>
                <input type="password" placeholder="Password" class="password">
            </div>
            <div class="inputs" style="position: relative; top: -130px;">
                <p>Forget Password</p>
                <input type="password" placeholder="Password">
            </div>

            <div class="d-flex justify-content-center " style="position: relative; top: -150px;">
                <p><input type="checkbox">Are you agree to Clicon Terms of Condition and Privacy Policy.                </p>
            </div>

            <div class="d-flex justify-content-center">
                <button>Sign Up</button>
            </div>

            <p style="color: #E4E7E9; position: relative; top: -100px; width: 80%;">____________________________or__________________________</p>
        </div>
    </div>



@endsection