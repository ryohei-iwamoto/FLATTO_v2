<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetAddress{
    protected $APIKey;

    public function __construct(){
        $this->APIKey = config('myapp.google_maps_api_key');
    }

    public function get_address($place_name){
        $request_url = "https://maps.googleapis.com/maps/api/geocode/json";

        $params = [
            'key'       =>  $this->APIKey,
            'address'   =>  $place_name,
            'language'  =>  'jp'
        ];
    }
}
