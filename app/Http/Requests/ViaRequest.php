<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'means'         => 'required|string',
            'limit'         => 'required|integer',
            'origin'        => 'nullable|string',
            'user_lat'      => 'nullable|string',
            'user_long'     => 'nullable|string',
            'destination'   => 'required|string',
            'use_gps'       => 'required|boolean',
            'via_btn'       => 'array'
        ];
    }

    public function messages()
    {
        return [
            'means.required'        => '移動方法を設定してください。',
            'limit.required'        => '所要時間を入力してください。',
            'origin.required'       => '出発地を入力してください。',
            'destination.required'  => '目的地を入力してください。',
            'use_gps'               => 'システムエラー(debug:use_gps not defined)'
        ];
    }
}
