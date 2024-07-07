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
            'language'  =>  'jp',
            'region' => 'jp'
        ]);

        return $geocode_api_response->json();
    }

}
