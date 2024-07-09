<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\GetRouteService;

class ChangeViaSpotController extends Controller{
    private $apiKey;
    private $getRouteService;

    public function __construct(){
        $this->apiKey = config('myapp.google_maps_api_key');
        $this->getRouteService = 
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
            $via_place['lat'],
            $via_place['lng']
        );
    }
}
