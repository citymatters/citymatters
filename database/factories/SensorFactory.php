<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

use Faker\Generator as Faker;

$factory->define(\App\Sensor::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'codename' => \App\Helper\CodenameHelper::generateCodename(),
        'last_seen' => $faker->dateTimeBetween()->getTimestamp(),
    ];
});
