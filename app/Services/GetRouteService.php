<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetRouteService
{
    protected $APIKey;

    public function __construct()
    {
        $this->APIKey = config('myapp.google_maps_api_key');
    }

    public function GetRoute($original_lat, $original_long, $destination_lat, $destination_long, $means, $via_place = "")
    {
        $request_url = 'https://maps.googleapis.com/maps/api/directions/json';

        if ($via_place) {
            $directions_api_response = Http::get($request_url, [
                'origin' => (string)$original_lat . "," . (string)$original_long,
                'destination' => (string)$destination_lat . "," . (string)$destination_long,
                'waypoints' => (string)$via_place['lat'] . "," . (string)$via_place['lng'],
                'departure_time' => 'now',
                'mode' => $means,
                'key' => $this->APIKey,
                'language' => 'ja',
                'region' => 'jp'
            ]);
        } else {
            $directions_api_response = Http::get($request_url, [
                'origin' => $original_lat . "," . $original_long,
                'destination' => $destination_lat . "," . $destination_long,
                'departure_time' => 'now',
                'mode' => $means,
                'key' => $this->APIKey,
                'language' => 'ja',
                'region' => 'jp'
            ]);
        }

        $directions_api_json_response = $directions_api_response->json();


        return $directions_api_json_response;
    }
}
