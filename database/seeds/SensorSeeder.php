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
        $user = \App\User::where('name', 'Gregor')->firstOrFail();
        factory(App\Sensor::class, 50)->create([
            'user_id' => $user->_id,
        ]);
    }
}
