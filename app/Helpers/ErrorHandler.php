<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

/**
 * エラーレスポンスを生成します。
 *
 * @param string $error_code エラーコード（言語ファイルのキーに対応）
 * @param int $status_code HTTPステータスコード
 * @return \Illuminate\Http\Response エラーレスポンスを返します
 */

class ErrorHandler{
    public static function createErrorResponse($error_code, $status_code){
        Log::info($error_code);
        $error_message = trans('message.'. $error_code);
        return response()->view('apology', ['error_code'=>$status_code, 'error_message'=>$error_message]);
    }
}