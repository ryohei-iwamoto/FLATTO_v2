<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetPlaceDetailService{
    protected $APIKey;

    public function __construct(){
        $this->APIKey = config('myapp.google_maps_api_key');
    }

    public function GetPlaceDetail($place_id){
        $request_url = "https://maps.googleapis.com/maps/api/place/details/json";

        $geocode_api_response = Http::get($request_url, [
            'place_id'   =>  $place_id,
            'key' => $this->APIKey,
            'language'  =>  'ja',
            'region' => 'jp'
        ]);

        $response_data = json_decode($geocode_api_response->body(), true);

        // エラーチェックとデータ処理
        if (isset($response_data['error_message'])) {
            Log::error('Google Places API error', ['error' => $response_data['error_message']]);
            return 'no_review';
        } elseif (isset($response_data['result']['reviews'])) {
            return $response_data['result']['reviews'];
        }

        return 'no_review';
    }

}
