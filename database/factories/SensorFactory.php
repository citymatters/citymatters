<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

use Faker\Generator as Faker;

$factory->define(\App\Sensor::class, function (Faker $faker) {
    return [
        'codename' => \App\Helper\CodenameHelper::generateCodename(),
        'uuid' => $faker->uuid,
        'last_measpoint' => $faker->dateTimeBetween()->getTimestamp(),
    ];
});
