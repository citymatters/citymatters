<?php
/**
 * Created by PhpStorm.
 * User: gwaldvogel
 * Date: 20.11.18
 * Time: 14:32
 */

namespace App\Helper;


use Carbon\Carbon;

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
            $datetime = Carbon::createFromTimestamp($measpoint->datetime);
            $feature = [
                'type' => 'Feature',
                'properties' => [
                    'day' => $datetime->day,
                    'month' => $datetime->month,
                    'year' => $datetime->year,
                ],
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
                if(isset($measpoint->$val) && $measpoint->$val != null)
                {
                    $feature['properties'][$val] = $measpoint->$val;
                }
                else
                {
                    $feature['properties'][$val] = rand(0,30);
                }
            }

            $geojson['features'][] = $feature;
        }
        return $geojson;
    }
}