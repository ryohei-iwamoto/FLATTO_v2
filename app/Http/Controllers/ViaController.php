<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\GetAddress;

class ViaController extends Controller
{
    protected $get_address;

    public function __construct(GetAddress $get_address){
        
    }

    public function via(Request $request){
        $means = $request->input('means');
        $limit = $request->input('limit');
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $keyword_list = $request->input('via_btn');
        $keyword = '';

        if (strpos($means, ' | ') === true){
            $temp_means = explode(' | ', $means);
            $means = $temp_means[0];
            $origin = $temp_means[1];
        }

        if (!$means) {
            $error_message = "移動方法を設定してください";
        } elseif (!$limit) {
            $error_message = "所要時間を入力してください";
        } elseif (!$origin) {
            $error_message = "出発地を入力してください";
        } elseif (!$destination) {
            $error_message = "目的地を入力してください";
        }

        if ($error_message){
            return response()->view('apology', ['error_code'=>'400', 'error_message' => $error_message], 400);
        }
        


    }
}
