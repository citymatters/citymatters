<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $org = \App\Organization::where('name', 'city_matters')->first();
        if (! $org) {
            $org = new \App\Organization();
            $org->name = 'city_matters';
            $org->slug = 'ctymttrs';
            $org->save();
        }
        factory(App\Sensor::class, 50)->create([
            'organization_id' => $org->_id,
        ])->each(function ($s) {
            factory(App\Measpoint::class, 12 * 24 * 30)->create([
                'sensor_id' => $s->_id,
            ]);
        });

        $user = \App\User::where('name', 'Gregor')->firstOrFail();
        factory(App\Sensor::class, 50)->create([
            'user_id' => $user->_id,
        ])->each(function ($s) {
            factory(App\Measpoint::class, 12 * 24 * 30)->create([
                'sensor_id' => $s->_id,
            ]);
        });
    }
}
