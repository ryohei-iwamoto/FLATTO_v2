<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthViewController extends Controller
{
    public function ShowSignUp(){
        return view('account', ['page_type' => 'register']);
    }

    public function ShowLogIn(){
        return view('account', ['page_type => login']);
    }
}
