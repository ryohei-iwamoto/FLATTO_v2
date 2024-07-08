<?php

namespace App\Http\Controllers;

use App\Http\Requests\ViaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\GetAddressService;
use App\Services\GetRouteService;
use App\Services\GeoCalculationService;
use App\Services\GooglePlacesService;
use App\Services\ReformatPlacesApiDataService;
use App\Services\GetPlaceDetailService;
use App\Services\SearchViaSpotsService;
use App\Services\SearchReachViaSpotService;

use App\Helpers\ErrorHandler;

class ViaController extends Controller
{
    protected $addressService;
    protected $getRouteService;
    protected $getPlaceDetailService;
    protected $searchViaSpotsService;
    protected $searchReachViaSpotService;
    protected $APIKey;

    public function __construct(GetAddressService $addressService, GetRouteService $getRouteService, GetPlaceDetailService $getPlaceDetailService, SearchViaSpotsService $searchViaSpotsService, SearchReachViaSpotService $searchReachViaSpotService)
    {
        $this->addressService = $addressService;
        $this->getRouteService = $getRouteService;
        $this->getPlaceDetailService = $getPlaceDetailService;
        $this->searchViaSpotsService = $searchViaSpotsService;
        $this->searchReachViaSpotService = $searchReachViaSpotService;
        $this->APIKey = config('myapp.google_maps_api_key');
    }

    public function via(ViaRequest $request)
    {
        $means = $request->input('means');
        $limit = $request->input('limit');
        $origin = $request->input('origin');
        $user_lat = $request->input('user_lat');
        $user_long = $request->input('user_long');
        $destination = $request->input('destination');
        $keyword_list = $request->input('via_btn', []);
        $use_gps = $request->input('use_gps');
        $keywords = '';

        $keywords = implode("|", $keyword_list);


        if ($use_gps) {
            $original_lat = $user_lat;
            $original_long = $user_long;
        } else {
            $original_cie = $this->addressService->GetAddress($origin);
            if ($original_cie['status'] == 'ZERO_RESULTS') {
                return ErrorHandler::createErrorResponse('not_found_start_point_address', 501);
            } else {
                $original_lat = $original_cie['results'][0]['geometry']['location']['lat'];
                $original_long = $original_cie['results'][0]['geometry']['location']['lng'];
            }
        }

        $destination_cie = $this->addressService->GetAddress($destination);

        if ($destination_cie['status'] == 'ZERO_RESULTS') {
            return ErrorHandler::createErrorResponse('not_found_end_point_address', 501);
        }

        $destination_lat = $destination_cie['results'][0]['geometry']['location']['lat'];
        $destination_long = $destination_cie['results'][0]['geometry']['location']['lng'];

        $directions = $this->getRouteService->GetRoute($original_lat, $original_long, $destination_lat, $destination_long, $means);

        if ($directions['status'] == "ZERO_RESULTS") {
            return ErrorHandler::createErrorResponse('cannot_travel_in_time', 400);
        }

        $direction_time = $directions['routes'][0]['legs'][0]['duration']['value'];

        if ($direction_time > $limit * 60) {
            return ErrorHandler::createErrorResponse('route_not_found', 501);
        }

        $via_limit = ($limit - ($direction_time / 60));

        $reformated_via_places_api_data = $this->searchViaSpotsService->SearchViaSpot(
            $original_lat,
            $original_long,
            $destination_lat,
            $destination_long,
            $means,
            $via_limit,
            $keywords
        );


        $via_place = $this->searchReachViaSpotService->searchReachVia(
            $reformated_via_places_api_data,
            $original_lat,
            $original_long,
            $destination_lat,
            $destination_long,
            $means,
            $directions,
            $via_limit
        );

        $via_place_lat = $via_place['lat'];
        $via_place_long = $via_place['lng'];

        $reformated_via_candidates_places_api_data = $this->searchViaSpotsService->SearchViaSpot(
            $via_place_lat,
            $via_place_long,
            $via_place_lat,
            $via_place_long,
            $means,
            15,
            ""
        );

        $url = ("https://www.google.com/maps/dir/?api=1&origin=" . (string)$original_lat . "," . (string)$original_long . "&destination=" . (string)$destination_lat . "," . (string)$destination_long . "&travelmode=" . $means . "&waypoints=" . (string)$via_place_lat . "," . (string)$via_place_long);
        $session_id = 0;
        $favorite = 0;
        $rate = $this->getPlaceDetailService->GetPlaceDetail($via_place['place_id']);

        $mapURL = "https://www.google.com/maps/embed/v1/directions?key={$this->APIKey}"
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
