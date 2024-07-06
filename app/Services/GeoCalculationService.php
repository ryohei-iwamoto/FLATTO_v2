<?php
namespace App\Services;

class GeoCalculationService{

    const EARTH_RADIUS = 6378;
    const PI = 3.141592653589793238462643383;

    public function calculateDistancePerDegree($latitude){

        // 経度1度あたりの距離を計算
        return cos($latitude / 180 * self::PI) * 2 * self::PI * self::EARTH_RADIUS / 360;
    }

    public function calculateViaCenter($start_point_lat, 
                                        $start_point_long,
                                        $destination_lat, 
                                        $destination_long, 
                                        $means_of_transportation, 
                                        $limit){

        $latRadius = $this->calculateDistancePerDegree($start_point_lat);
        $kmPerDegree = $latRadius / 360;
        $equatorKmPerDegree = 40075 / 360; // 赤道の周囲を360で割る
        $kmRatio = $kmPerDegree / $equatorKmPerDegree;

        $radius = 0;
        $viaCenterDistance = 0;

        switch ($means_of_transportation) {
            case 'driving':
                $radius = 250 * $limit;
                $viaCenterDistance = 35 / 60 * $limit * $kmRatio;
                break;
            case 'bicycling':
                $radius = 150 * $limit;
                $viaCenterDistance = 20 / 60 * $limit * $kmRatio;
                break;
            case 'walking':
                $radius = 80 * $limit;
                $viaCenterDistance = 5 / 60 * $limit * $kmRatio;
                break;
        }

        if ($radius > 50000) {
            $radius = 50000; // 最大半径を50kmに制限
        }

        // 中心点の計算
        $centerLatitude = ($start_point_lat + $destination_lat) / 2;
        $centerLongitude = ($start_point_long + $destination_long) / 2;

        // 経由地点の計算
        $viaLatitude = $centerLatitude + $viaCenterDistance * cos(atan2($destination_long - $start_point_long, $destination_lat - $start_point_long));
        $viaLongitude = $centerLongitude + $viaCenterDistance * sin(atan2($destination_long - $start_point_long, $destination_lat - $start_point_lat));

        return ['lat' => $viaLatitude, 
                'lng' => $viaLongitude, 
                'radius' => $radius
            ];
    }
}
