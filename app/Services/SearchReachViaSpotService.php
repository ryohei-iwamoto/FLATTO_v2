<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

use App\Services\GetRouteService;
use App\Helpers\ErrorHandler;

class SearchReachViaSpotService{
    protected $getRouteService;

    public function __construct(GetRouteService $getRouteService){
        $this->getRouteService = $getRouteService;
    }

    public function searchReachVia($reformated_via_places_api_data, $original_lat, $original_long, $destination_lat, $destination_long, $means, $via_place, $directions){
        for ($n == 1; $n < 10; $n++) {
            try {
                $randomKey = array_rand($reformated_via_places_api_data);
                $via_place = $reformated_via_places_api_data[$randomKey];
            } catch (\Exception $e) {
                return ErrorHandler::createErrorResponse('via_spot_not_found', 400);
            }

            $via_route = $this->getRouteService->GetRoute($original_lat, $original_long, $destination_lat, $destination_long, $means, $via_place);

            if (!($via_route['status'] == 'ZERO_RESULTS') && $via_limit * 70 >= $via_route['routes'][0]['legs'][0]['duration']['value']) {
                $add_route_data =  [
                    'add_distance' => $directions['routes'][0]['legs'][0]['distance'],
                    'add_duration' => $directions['routes'][0]['legs'][0]['duration']
                ];

                foreach ($add_route_data as $key => $value) {
                    $via_place[$key] = $value;
                }

                return $via_place;
            }else{
                $key = array_search($via_route, $reformated_via_places_api_data);
                unset($reformated_via_places_api_data[$key]);
                }
        }

        if ($n == 10){
            return ErrorHandler::createErrorResponse('reach_via_spot_not_found', 400);
        }
    }
}
