<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeaspointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measpoints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('sensor_id');
            $table->double('lat', 15, 13);
            $table->double('lon', 15, 13);
            $table->dateTime('datetime');
            $table->timestamps();

            $table->index(['sensor_id']);
            $table->index(['lat', 'lon']);
            $table->index(['lat', 'lon', 'datetime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measpoints');
    }
}
