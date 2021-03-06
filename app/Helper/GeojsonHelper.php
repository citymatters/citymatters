<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
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
        foreach ($measpoints as $measpoint) {
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
                        0,
                    ],
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
                'temperature',
            ];
            foreach ($values as $val) {
                if (isset($measpoint->$val) && $measpoint->$val != null) {
                    switch ($val) {
                        case 'pm2':
                        case 'pm10':
                        case 'ozone':
                        case 'sulfurDioxide':
                        case 'carbbonMonoxide':
                        case 'nitrogenDioxide':
                        case 'humidity':
                            if ($measpoint->$val < 0) {
                                $measpoint->$val = 0;
                            }
                            break;
                    }
                    $feature['properties'][$val] = round($measpoint->$val, 1);
                } else {
                    $feature['properties'][$val] = rand(0, 30);
                }
            }

            $geojson['features'][] = $feature;
        }

        return $geojson;
    }
}
