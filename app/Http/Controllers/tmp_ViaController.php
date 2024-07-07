<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\GetAddressService;
use App\Services\GetRouteService;
use App\Services\GeoCalculationService;
use App\Services\GooglePlacesService;
use App\Services\ReformatPlacesApiDataService;

class ViaController extends Controller{
    protected $addressService;
    protected $getRouteService;
    protected $geoService;
    protected $placesService;
    protected $reformatPlacesApiDataService;

    public function __construct(
        GetAddressService $addressService,
        GetRouteService $getRouteService,
        GeoCalculationService $geoService,
        GooglePlacesService $placesService,
        ReformatPlacesApiDataService $reformatPlacesApiDataService
    ) {
        $this->addressService = $addressService;
        $this->getRouteService = $getRouteService;
        $this->geoService = $geoService;
        $this->placesService = $placesService;
        $this->reformatPlacesApiDataService = $reformatPlacesApiDataService;
    }

    public function processViaRequest($request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $means = $request->input('means');
        $limit = $request->input('limit');
        $keyword_list = $request->input('via_btn', []);

        $original_cie = $this->addressService->GetAddress($origin);
        $destination_cie = $this->addressService->GetAddress($destination);

        if (!$original_cie || !$destination_cie) {
            return [
                'error' => true,
                'status_code' => 501,
                'error_message' => "住所が見つかりませんでした。",
                'error_code' => '501'
            ];
        }

        $original_lat = $original_cie['results'][0]['geometry']['location']['lat'];
        $original_long = $original_cie['results'][0]['geometry']['location']['lng'];
        $destination_lat = $destination_cie['results'][0]['geometry']['location']['lat'];
        $destination_long = $destination_cie['results'][0]['geometry']['location']['lng'];

        $directions = $this->getRouteService->GetRoute($original_lat, $original_long, $destination_lat, $destination_long, $means);
        if ($directions['status'] == "ZERO_RESULTS") {
            return [
                'error' => true,
                'status_code' => 501,
                'error_message' => "経路が見つかりませんでした。",
                'error_code' => '501'
            ];
        }

        $direction_time = $directions['routes'][0]['legs'][0]['duration']['value'];
        if ($direction_time > $limit * 60) {
            return [
                'error' => true,
                'status_code' => 400,
                'error_message' => "指定された時間内に到達できません",
                'error_code' => '400'
            ];
        }

        $suggest_via_place = $this->geoService->calculateViaCenter($original_lat, $original_long, $destination_lat, $destination_long, $means, $limit);
        $places_api_raw_json_data = $this->placesService->searchNearbyPlaces($suggest_via_place['lat'], $suggest_via_place['lng'], $suggest_via_place['radius'], implode('|', $keyword_list));
        $reformated_places_api_data = $this->reformatPlacesApiDataService->ReformatPlacesApiData($places_api_raw_json_data);

        Log::info($reformated_places_api_data);
    }
}
