<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetAddressService{
    protected $APIKey;

    public function __construct(){
        $this->APIKey = config('myapp.google_maps_api_key');
    }

    public function GetAddress($place_name){
        $request_url = "https://maps.googleapis.com/maps/api/geocode/json";

        $geocode_api_response = Http::get($request_url, [
            'address'   =>  $place_name,
            'key' => $this->APIKey,
            'language'  =>  'jp',
            'region' => 'jp'
        ]);

        return $geocode_api_response->json();
    }

}
