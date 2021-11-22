<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('building_name');
            $table->integer('fe');
            $table->integer('lut');
            $table->integer('cry');
            $table->integer('h2o');
            $table->integer('h2');
            $table->integer('prod_fe');
            $table->integer('prod_lut');
            $table->integer('prod_cry');
            $table->integer('prod_h2o');
            $table->integer('prod_h2');
            $table->integer('cost_fe');
            $table->integer('cost_lut');
            $table->integer('cost_cry');
            $table->integer('cost_h2o');
            $table->integer('cost_h2');
            $table->integer('store_fe');
            $table->integer('store_lut');
            $table->integer('store_cry');
            $table->integer('store_h2o');
            $table->integer('store_h2');
            $table->boolean('allows_research');
            $table->boolean('allows_ships');
            $table->boolean('allows_defense');
            $table->boolean('allows_radar')->default(false);
            $table->integer('decrease_research_timeBy');
            $table->integer('decrease_ships_timeBy');
            $table->integer('decrease_defense_timeBy');
            $table->integer('decrease_building_timeBy');
            $table->integer('dynamic_buildtime');
            $table->integer('initial_buildtime');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
