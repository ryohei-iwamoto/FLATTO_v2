<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ReformatPlacesApiDataService{
    public function reformatPlacesApiData($placesApiRawJsonData){
        
        $results = [];
        $suggestedPlaces = [];

        // Log::debug($placesApiRawJsonData);

        foreach ($placesApiRawJsonData['results'] as $placeResult) {
            if (!in_array('political', $placeResult['types'])) {
                $results[] = $placeResult;
            }
        }

        // それぞれの経由地の緯度経度、名前、評価、住所を辞書式で保存
        foreach ($results as $result) {
            $placeData = $result['geometry']['location'];
            $placeData['name'] = $result['name'];
            $placeData['rating'] = $result['rating'] ?? '';
            $placeData['vicinity'] = $result['vicinity'];

            // 写真が存在すればその参照情報を、存在しなければ空文字を代入
            $placeData['photo_reference'] = isset($result['photos']) ? $result['photos'][0]['photo_reference'] : '';

            // place_idが存在すればその値を、存在しなければ空文字を代入
            $placeData['place_id'] = $result['place_id'] ?? '';

            // 配列に保存
            $suggestedPlaces[] = $placeData;
        }

        return $suggestedPlaces;
    }
}
