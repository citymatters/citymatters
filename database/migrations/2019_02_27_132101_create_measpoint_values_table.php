<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeaspointValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measpoint_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('measpoint_id');
            $table->double('value');
            $table->string('unit');
            $table->string('type');
            $table->boolean('valid')->default(true);
            $table->timestamps();
            $table->index(['measpoint_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measpoint_values');
    }
}
