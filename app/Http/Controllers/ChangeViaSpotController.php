<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\GetRouteService;
use App\Services\GetPlaceDetailService;

use App\Helpers\ErrorHandler;

class ChangeViaSpotController extends Controller
{
    private $apiKey;
    private $getRouteService;
    private $getPlacesDetailService;

    public function __construct(GetRouteService $getRouteService, GetPlaceDetailService $getPlacesDetailService)
    {
        $this->apiKey = config('myapp.google_maps_api_key');
        $this->getRouteService = $getRouteService;
        $this->getPlacesDetailService = $getPlacesDetailService;
    }

    public function changeVia(Request $request)
    {
        $via_place = json_decode($request->input("via_place"), true);
        $original_lat = $request->input("original_lat");
        $original_long = $request->input("original_long");
        $destination_lat = $request->input("destination_lat");
        $destination_long = $request->input("destination_long");
        $means = $request->input("means");
        $origin = $request->input("origin");
        $destination = $request->input("destination");
        $reformated_via_candidates_places_api_data = json_decode($request->input("reformated_via_candidates_places_api_data"), true);

        $directions = $this->getRouteService->GetRoute(
            $original_lat,
            $original_long,
            $destination_lat,
            $destination_long,
            $means,
            $via_place
        );

        if ($directions['status'] == 'OK') {
            $add_route_data =  [
                'add_distance' => $directions['routes'][0]['legs'][0]['distance'],
                'add_duration' => $directions['routes'][0]['legs'][0]['duration']
            ];

            foreach ($add_route_data as $key => $value) {
                $via_place[$key] = $value;
            }


            $rate = $this->getPlacesDetailService->GetPlaceDetail($via_place['place_id']);

            // TODO:urlをhelperで実装する？
            $url = ("https://www.google.com/maps/dir/?api=1&origin=" . (string)$original_lat . "," . (string)$original_long . "&destination=" . (string)$destination_lat . "," . (string)$destination_long . "&travelmode=" . $means . "&waypoints=" . (string)$via_place['lat'] . "," . (string)$via_place['lng']);
            $mapURL = "https://www.google.com/maps/embed/v1/directions?key={$this->apiKey}"
                . "&origin={$original_lat},{$original_long}"
                . "&destination={$destination_lat},{$destination_long}"
                . "&mode={$means}"
                . "&waypoints={$via_place['lat']},{$via_place['lng']}";

            //　TODO:ログイン機能実装後、お気に入り機能とセッションの有無を実装する。
            $favorite = 0;
            $session_id = 0;

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
        } else {
            return ErrorHandler::createErrorResponse('route_not_found', 501);
        }
    }
}
