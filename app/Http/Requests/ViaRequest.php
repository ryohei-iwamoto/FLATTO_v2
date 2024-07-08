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
            'user_lat'      => 'nullable|float',
            'user_long'     => 'nullable|float',
            'destination'   => 'required|string',
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
        ];
    }
}
