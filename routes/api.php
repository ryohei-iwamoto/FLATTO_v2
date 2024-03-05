<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Search\SearchSurroundSpotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

////////////////////////////////////////////////////////////////////////////////////
// 機能一覧
//
// 登録・ログイン・ログアウト・メール認証機能
// 周辺地域・経由地・レビュー検索機能
// お気に入り・履歴追加機能
// アイコン・ユーザー名変更機能
// マイページ機能(ランキング・履歴・お気に入り)
////////////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// アカウント系(ログイン・ログアウト・登録)
Route::prefix('Account')->group(function () {
    Route::post('/login', OperationController::class);
    Route::post('/logout', OperationController::class);
    Route::post('/temporary_registration', OperationController::class);
    Route::get('/email/verification', OperationController::class);
    Route::post('/apply_activation', OperationController::class);
});

Route::prefix('search')->group(function () {
    Route::post('/surrounding_spot/info', OperationController::class);
    Route::post('/travel_availability/info', OperationController::class);
    Route::post('/via_spot/info', OperationController::class);
    Route::post('/review/info', OperationController::class);
});

Route::prefix('user')->group(function (){
    Route::prefix('history')->group(function () {
        Route::get('/info', OperationController::class);
        Route::get('/register', OperationController::class);
        Route::get('/delete', OperationController::class);
    });

    Route::prefix('favorite')->group(function () {
        Route::get('/info', OperationController::class);
        Route::get('/register', OperationController::class);
        Route::get('/delete', OperationController::class);
    });

    Route::prefix('mypage')->group(function () {
        Route::get('/info', OperationController::class);
        Route::get('/update', OperationController::class);
    });
});