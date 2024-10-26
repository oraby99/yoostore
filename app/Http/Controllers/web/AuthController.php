<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signupView()
    {        
        return view('yoostore.signup');
    }

    public function loginView()
    {        
        return view('yoostore.login');
    }


}
