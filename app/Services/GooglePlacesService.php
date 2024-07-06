<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePlacesService{
    protected $apiKey;
    
    public function __construct(){
        $this->apiKey = config('myapp.google_maps_api_key');
    }

    public function searchNearbyPlaces($latitude, $longitude, $radius, $keyword)
    {
        // Google Places API URL
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';

        // HTTP GETリクエストを送信
        $places_api_response = Http::get($url, [
            'location' => "{$latitude},{$longitude}",
            'radius' => $radius,
            'keyword' => $keyword,
            'language' => 'ja',
            'key' => $this->apiKey
        ]);

        // 応答をJSON形式でデコードしてresultsを返す
        // return $places_api_response->json()['results'];
        return $places_api_response->json();
    }
}
