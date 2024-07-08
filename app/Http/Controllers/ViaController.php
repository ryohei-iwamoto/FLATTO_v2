<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\GetAddressService;
use App\Services\GetRouteService;
use App\Services\GeoCalculationService;
use App\Services\GooglePlacesService;
use App\Services\ReformatPlacesApiDataService;
use App\Services\GetPlaceDetailService;

class ViaController extends Controller {
    protected $addressService;
    protected $getRouteService;
    protected $geoService;
    protected $placesService;
    protected $reformatPlacesApiDataService;
    protected $getPlaceDetailService;
    protected $APIKey;

    public function __construct(GetAddressService $addressService, GetRouteService $getRouteService, GeoCalculationService $geoService, GooglePlacesService $placesService, ReformatPlacesApiDataService $ReformatPlacesApiDataService, GetPlaceDetailService $getPlaceDetailService) {
        $this->addressService = $addressService;
        $this->getRouteService = $getRouteService;
        $this->geoService = $geoService;
        $this->placesService = $placesService;
        $this->reformatPlacesApiDataService = $ReformatPlacesApiDataService;
        $this->getPlaceDetailService = $getPlaceDetailService;
        $this->APIKey = config('myapp.google_maps_api_key');
    }

    public function via(Request $request) {
        $means = $request->input('means');
        $limit = $request->input('limit');
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $keyword_list = $request->input('via_btn', []);
        $keyword = '';
        $error_message = '';

        if (strpos($means, ' | ') === true) {
            $temp_means = explode(' | ', $means);
            $means = $temp_means[0];
            $origin = $temp_means[1];
        }

        if (!$means) {
            $error_message = "移動方法を設定してください";
        } elseif (!$limit) {
            $error_message = "所要時間を入力してください";
        } elseif (!$origin) {
            $error_message = "出発地を入力してください";
        } elseif (!$destination) {
            $error_message = "目的地を入力してください";
        }

        if ($error_message) {
            return response()->view('apology', ['error_code' => '400', 'error_message' => $error_message], 400);
        }

        foreach ($keyword_list as $value) {
            $keyword .= "|" . $value;
        }
        $keyword = substr($keyword, 1);

        $original_cie = $this->addressService->GetAddress($origin);
        $destination_cie = $this->addressService->GetAddress($destination);

        $error_message = '';

        if (!$original_cie) {
            $error_message = "出発地点の住所が見つかりませんでした。\n渋谷区 代々木 参宮橋 まいばすけっとのように特定しやすくするか、\n駅など他の場所を入力してください";
        }

        if (!$destination_cie) {
            $error_message = "目的地の住所が見つかりませんでした。\n渋谷区 代々木 参宮橋 まいばすけっとのように特定しやすくするか、\n駅など他の場所を入力してください";
        }

        if ($error_message) {
            return response()->view('apology', ['error_code' => '501', 'error_message' => $error_message], 501);
        }

        $original_lat = $original_cie['results'][0]['geometry']['location']['lat'];
        $original_long = $original_cie['results'][0]['geometry']['location']['lng'];
        $destination_lat = $destination_cie['results'][0]['geometry']['location']['lat'];
        $destination_long = $destination_cie['results'][0]['geometry']['location']['lng'];

        $directions = $this->getRouteService->GetRoute($original_lat, $original_long, $destination_lat, $destination_long, $means);
        $direction_time = $directions['routes'][0]['legs'][0]['duration']['value'];

        if ($directions['status'] == "ZERO_RESULTS") {
            return response()->view('apology', ['error_code' => '501', 'error_message' => "経路が見つかりませんでした。"], 501);
        }

        if ($direction_time > $limit * 60) {
            return response()->view('apology', ['error_code' => '501', 'error_message' => "入力した時間では目的地に到着できません"], 400);
        }

        $via_limit = ($limit - ($direction_time / 60));

        $calc_via_center = $this->geoService->calculateViaCenter(
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
            $keyword
        );

        $reformated_via_places_api_data = $this->reformatPlacesApiDataService->ReformatPlacesApiData($via_places_api_raw_json_data);

        $n = 0;
        while ($n != 10) {
            try {
                $randomKey = array_rand($reformated_via_places_api_data);
                $via_place = $reformated_via_places_api_data[$randomKey];
            } catch (\Exception $e) {
                return response()->view('apology', ['error_code' => '400', 'error_message' => "経由地スポットが見つかりませんでした...所要時間を増やしてみてください"], 400);
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

                break;
            }else{
                $key = array_search($via_route, $reformated_via_places_api_data);
                unset($reformated_via_places_api_data[$key]);
                }
            $n += 1;
        }

        if ($n == 10){
            return response()->view('apology',  ['error_code' => '501', 'error_message' => "時間内にいける経由地スポットが見つかりませんでした。所要時間を変更するか、目的地を変更してください。"], 501);
        }

        Log::info($via_place);

        $via_place_lat = $via_place['lat'];
        $via_place_long = $via_place['lng'];

        $calc_via_candidates_center = $this->geoService->calculateViaCenter(
            $via_place_lat,
            $via_place_long,
            $via_place_lat,
            $via_place_long,
            $means,
            15
        );

        $via_candidates_places_api_raw_json_data = $this->placesService->searchNearbyPlaces(
            $calc_via_candidates_center['lat'],
            $calc_via_candidates_center['lng'],
            $calc_via_candidates_center['radius'],
            ""
        );

        $reformated_via_candidates_places_api_data = $this->reformatPlacesApiDataService->ReformatPlacesApiData($via_candidates_places_api_raw_json_data);

        $url = ("https://www.google.com/maps/dir/?api=1&origin=".(string)$original_lat.",".(string)$original_long."&destination=".(string)$destination_lat.",".(string)$destination_long."&travelmode=". $means ."&waypoints=".(string)$via_place_lat.",".(string)$via_place_long);
        $session_id = 0;
        $favorite = 0;
        $rate = $this->getPlaceDetailService->GetPlaceDetail($via_place['place_id']);

        $mapURL = "https://www.google.com/maps/embed/v1/directions?key={$this->APIKey}"
        . "&origin={$original_lat},{$original_long}"
        . "&destination={$destination_lat},{$destination_long}"
        . "&mode={$means}"
        . "&waypoints={$via_place['lat']},{$via_place['lng']}";

        Log::info($via_candidates_places_api_raw_json_data);


        return view('via', compact('via_place',
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
                                    'origin'));
    }
}
