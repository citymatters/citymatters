<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateMeaspointIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measpoints', function ($collection) {
            $collection->index(['lat', 'lon']);
            $collection->index(['lat', 'lon', 'datetime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
