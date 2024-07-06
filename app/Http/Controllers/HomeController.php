<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

use App\Services\GeoCalculationService;
use App\Services\GooglePlacesService;
use App\Services\ReformatPlacesApiDataService;

class HomeController extends Controller{
    protected $geoService;
    protected $placesService;
    protected $reformatPlacesApiDataService;

    public function __construct(GeoCalculationService $geoService, GooglePlacesService $placesService, ReformatPlacesApiDataService $ReformatPlacesApiDataService){
        $this->geoService = $geoService;
        $this->placesService = $placesService;
        $this->reformatPlacesApiDataService = $ReformatPlacesApiDataService;
    }

    public function index(Request $request){
        if ($request->isMethod('post')) {
            $user_lat = $request->input('user_lat', 35.0036559);
            $user_long = $request->input('user_long', 135.7785534);
            $use_gps = 1;
        }

        if ($request->isMethod('get')) {
            $user_lat = 35.0036559;
            $user_long = 135.7785534;
            $use_gps = 0;
        }

        $keyword = "";

        $location = $this->geoService->calculateViaCenter($user_lat, 
                                                            $user_long, 
                                                            $user_lat, 
                                                            $user_long, 
                                                            "driving", 
                                                            60
                                                        );


        $places_api_raw_json_data = $this->placesService->searchNearbyPlaces($location['lat'], 
                                                            $location['lng'], 
                                                            $location['radius'], 
                                                            ""
                                                        );


        // $reformated_places_api_data = $this->reformatPlacesApiDataService->ReformatPlacesApiData($places_api_raw_json_data);
        $places_api_data = $this->reformatPlacesApiDataService->ReformatPlacesApiData($places_api_raw_json_data);

        return view('home', compact('places_api_data',
                                    'use_gps',
                                    'user_lat',
                                    'user_long'));
    }
}
