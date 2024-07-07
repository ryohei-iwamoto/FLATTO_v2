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
                'origin' => str($original_lat) . "," . str($original_long),
                'destination' => str($destination_lat) . "," . str($destination_long),
                'waypoints' => str($via_place['lat']) . "," . str($via_place['long']),
                'departure_time' => 'now',
                'mode' => $means,
                'key' => $this->APIKey,
                'language' => 'jp',
                'region' => 'jp'
            ]);
        } else {
            $directions_api_response = Http::get($request_url, [
                'origin' => str($original_lat) . "," . str($original_long),
                'destination' => str($destination_lat . "," . $destination_long),
                'departure_time' => 'now',
                'mode' => $means,
                'key' => $this->APIKey,
                'language' => 'jp',
                'region' => 'jp'
            ]);
        }

        return $directions_api_response->json();
    }
}
