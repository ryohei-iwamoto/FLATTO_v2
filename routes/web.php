<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;

// Route::get('/', function () {
//     return view('home');
// });


Route::match(['get', 'post'], '/', [HomeController::class, 'index']);

// Route::get('/', 'TestController@index');
// Route::get('/{message}/{hello}', function($message, $msg2){
//     return view('test.index', ['msg'=>$message, 'msg2'=>$msg2]);
// });

Route::get('/index/{message?}', function($message="<script>alert(1)</script>"){
    return view('test.index', ['msg'=>$message]);
});

Route::get('/hello', function () {
    return view('hello');
});