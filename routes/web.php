<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthViewController;
use App\Http\Controllers\ViaController;
use App\Http\Controllers\ChangeViaSpotController;



Route::match(['get', 'post'], '/', [HomeController::class, 'index']);
Route::post('/via', [ViaController::class, 'via']);
Route::post('/change_via', [ChangeViaSpotController::class, 'changeVia']);
Route::get('/register', [AuthViewController::class, 'ShowSignUp']);
Route::get('/login', [AuthViewController::class, 'ShowLogIn']);


Route::get('/index/{message?}', function($message="<script>alert(1)</script>"){
    return view('test.index', ['msg'=>$message]);
});

Route::get('/hello', function () {
    return view('hello');
});
