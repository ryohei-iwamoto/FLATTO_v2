<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

use App\Services\GeoCalculationService;
use App\Services\GooglePlacesService;
use App\Services\ReformatPlacesApiDataService;

class SearchReachViaSpot{
    protected $geoCalcService;
    protected $placesService;
    protected $reformatPlacesApiDataService;

    public function __construct(GeoCalculationService $geoCalcService, GooglePlacesService $placesService, ReformatPlacesApiDataService $reformatPlacesApiDataService){
        $this->geoCalcService = $geoCalcService;
        $this->placesService = $placesService;
        $this->reformatPlacesApiDataService = $reformatPlacesApiDataService;
    }

    public function SearchViaSpot($original_lat, $original_long, $destination_lat, $destination_long, $means, $via_limit, $keywords){
        $calc_via_center = $this->geoCalcService->calculateViaCenter(
            $original_lat,
            $original_long,
            $destination_lat,
            $destination_long,
            $means,
            $via_limit
        );

        $via_places_api_raw_json_data = $this->placesService->searchNearbyPlaces(
            $calc_via_center['lat'],
            $calc_via_center['lng'],
            $calc_via_center['radius'],
            $keywords
        );

        $reformated_via_places_api_data = $this->reformatPlacesApiDataService->ReformatPlacesApiData($via_places_api_raw_json_data);

        return $reformated_via_places_api_data
    }
}