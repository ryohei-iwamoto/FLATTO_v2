<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\GetRouteService;
use App\Services\GetPlaceDetailService;

class ChangeViaSpotController extends Controller{
    private $apiKey;
    private $getRouteService;
    private $getPlacesDetailService;

    public function __construct(GetRouteService $getRouteService, GetPlaceDetailService $getPlacesDetailService){
        $this->apiKey = config('myapp.google_maps_api_key');
        $this->getRouteService = $getRouteService;
        $this->getPlacesDetailService = $getPlacesDetailService;
    }

    public function changeVia(Request $request){
        $via_place = $request->input("via_place");
        $original_lat = $request->input("original_lat");
        $original_long = $request->input("original_long");
        $destination_lat = $request->input("destination_lat");
        $destination_long = $request->input("destination_long");
        $means = $request->input("means");
        $origin = $request->input("origin");
        $destination = $request->input("destination");

        $directions = $this->getRouteService->GetRoute(
            $original_lat, 
            $original_long, 
            $destination_lat, 
            $destination_long, 
            $means,
            $via_place
        );

        $rate = $this->getPlacesDetailService->GetPlaceDetail($via_place['place_id']);

        $$url = ("https://www.google.com/maps/dir/?api=1&origin=" . (string)$original_lat . "," . (string)$original_long . "&destination=" . (string)$destination_lat . "," . (string)$destination_long . "&travelmode=" . $means . "&waypoints=" . (string)$via_place_lat . "," . (string)$via_place_long);
        $mapURL = "https://www.google.com/maps/embed/v1/directions?key={$this->apiKey}"
        . "&origin={$original_lat},{$original_long}"
        . "&destination={$destination_lat},{$destination_long}"
        . "&mode={$means}"
        . "&waypoints={$via_place['lat']},{$via_place['lng']}";

        return view('via', compact(
            'via_place',
            'url',
            'means',
            'favorite',
            'destination',
            'session_id',
            'original_lat',
            'mapURL',
            'original_long',
            'destination_lat',
            'destination_long',
            'rate',
            'reformated_via_candidates_places_api_data',
            'origin'
        ));
    }
}
