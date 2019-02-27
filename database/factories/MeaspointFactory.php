<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

use Faker\Generator as Faker;

$factory->define(\App\Measpoint::class, function (Faker $faker) {
    $pm2 = $faker->randomFloat(2, 1, 30);
    $pm10 = $pm2 + $faker->randomFloat(2, 1, 20);

    return [
        'sensor_id' => $faker->uuid,
        'lat' => $faker->randomFloat(6, 47.9, 48),
        'lon' => $faker->randomFloat(6, 7.6, 7.9),

        // Datetime between 1 month ago and tomorrow
        'datetime' => now()->addDays($faker->numberBetween(-30, 1))->toDateTimeString(),
//        'temperature' => $faker->randomFloat(2, 0, 35),
//        'humidity' => $faker->randomFloat(1, 10, 99.9),
//        'pm2' => $pm2,
//        'pm10' => $pm10,
    ];
});
