<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SensorSeeder::class);
    }
}
