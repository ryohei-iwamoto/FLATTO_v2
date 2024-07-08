<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class GooglePlacesHelper{
    /**
     * Generate a URL for Google Places photo
     *
     * @param string|null $photoReference The photo reference from Google Places API
     * @return string URL to the Google Places photo or a default image
     */
    public static function generatePhotoUrl(?string $photoReference): string{
        if (empty($photoReference)) {
            return asset('img/map_logo.jpg');
        }

        $apiKey = config('myapp.google_maps_api_key');
        Log::info("https://maps.googleapis.com/maps/api/place/photo?maxwidth=200&photoreference={$photoReference}&key={$apiKey}");
        return "https://maps.googleapis.com/maps/api/place/photo?maxwidth=200&photoreference={$photoReference}&key={$apiKey}";
        // return asset('img/map_logo.jpg');
    }
}
