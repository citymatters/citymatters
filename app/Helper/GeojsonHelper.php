<?php
/**
 * Created by PhpStorm.
 * User: gwaldvogel
 * Date: 20.11.18
 * Time: 14:32
 */

namespace App\Helper;


class GeojsonHelper
{
    public static function measpointsToGeojson($measpoints)
    {
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
        foreach($measpoints as $measpoint)
        {
            $feature = [
                'type' => 'Feature',
                'properties' => [],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $measpoint->lon,
                        $measpoint->lat,
                        0
                    ]
                ],
                'id' => $measpoint->_id,
            ];
            $values = [
                'pm2',
                'pm10',
                'ozone',
                'sulfurDioxide',
                'carbonMonoxide',
                'nitrogenDioxide',
                'humidity',
                'temperature'
            ];
            foreach($values as $val)
            {
                if(isset($measpoint->$val))
                {
                    $feature['properties'][$val] = $measpoint->$val;
                }
            }

            $geojson['features'][] = $feature;
        }
        return $geojson;
    }
}