<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('research', function (Blueprint $table) {
            $table->string('research_name')->default('No name');
            $table->integer('fe')->default(0);
            $table->integer('lut')->default(0);
            $table->integer('cry')->default(0);
            $table->integer('h2o')->default(0);
            $table->integer('h2')->default(0);
            $table->integer('increase_spy')->default(0);
            $table->integer('increase_counter_spy')->default(0);
            $table->integer('increase_ship_attack')->default(0);
            $table->integer('increase_ship_defense')->default(0);
            $table->integer('increase_shield_defense')->default(0);
            $table->integer('increase_rocket_drive')->default(0);
            $table->integer('increase_turbine_drive')->default(0);
            $table->integer('increase_warp_drive')->default(0);
            $table->integer('increase_transwarp_drive')->default(0);
            $table->integer('increase_ion_drive')->default(0);
            $table->integer('increase_max_planets')->default(0);
            $table->integer('increase_cargo')->default(0);
            $table->integer('static_bonus')->default(1);
            $table->json('building_requirements')->nullable();
            $table->json('research_requirements')->nullable();
            $table->integer('points')->default(0);
            $table->integer('initial_researchtime')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('research', function (Blueprint $table) {
            //
        });
    }
}
