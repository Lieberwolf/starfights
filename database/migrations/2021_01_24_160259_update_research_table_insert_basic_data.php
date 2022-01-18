<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateResearchTableInsertBasicData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('research', function (Blueprint $table) {

            DB::table('research')->insert([
                // empty sample
                /*
				[
					'research_name' => '',
					'fe' => ,
					'lut' => ,
					'cry' => ,
					'h2o' => ,
					'h2' => ,
					'increase_spy' => ,
					'increase_ship_attack' => ,
					'increase_ship_defense' => ,
					'increase_shield_defense' => ,
					'increase_rocket_drive' => ,
					'increase_turbine_drive' => ,
					'increase_warp_drive' => ,
					'increase_ion_drive' => ,
					'increase_max_planets' => ,
					'static_bonus' => ,
					'building_requirements' => ,
					'points' => ,
					'initial_researchtime' => ,
				]
				*/
                // Spionagetechnologie
                [
                    'research_name' => 'espionage',
                    'fe' => 1000,
                    'lut' => 500,
                    'increase_spy' => 10,
                    'static_bonus' => 1,
                    'points' => 3,
                    'initial_researchtime' => 1200
                ]
            ]);
            DB::table('research')->insert([
                // Turbinenantrieb
                [
                    'research_name' => 'turbine_engine',
                    'fe' => 4000,
                    'lut' => 2000,
                    'h2' => 500,
                    'increase_turbine_drive' => 10,
                    'static_bonus' => 1,
                    'points' => 5,
                    'initial_researchtime' => 2700,
                ]
            ]);
            DB::table('research')->insert([
                // Ionenantrieb
                [
                    'research_name' => 'ion_engine',
                    'fe' => 7500,
                    'lut' => 7500,
                    'cry' => 0,
                    'h2o' => 5000,
                    'h2' => 2500,
                    'increase_ion_drive' => 10,
                    'static_bonus' => 1,
                    'points' => 12,
                    'initial_researchtime' => 3600,
                ]
            ]);
            DB::table('research')->insert([
                // Warpantrieb
                [
                    'research_name' => 'warp_drive',
                    'fe' => 25000,
                    'lut' => 37500,
                    'cry' => 0,
                    'h2o' => 50000,
                    'h2' => 35000,
                    'increase_warp_drive' => 10,
                    'static_bonus' => 1,
                    'points' => 25,
                    'initial_researchtime' => 36000,
                ]
            ]);
            DB::table('research')->insert([
                // Transwarpantrieb
                [
                    'research_name' => 'transwarp_drive',
                    'fe' => 100000,
                    'lut' => 75000,
                    'cry' => 500,
                    'h2o' => 60000,
                    'h2' => 87500,
                    'increase_transwarp_drive' => 10,
                    'static_bonus' => 1,
                    'points' => 75,
                    'initial_researchtime' => 360000,
                ]
            ]);
            DB::table('research')->insert([
                // Raketenantrieb
                [
                    'research_name' => 'rocket_drive',
                    'fe' => 12500,
                    'lut' => 5000,
                    'cry' => 0,
                    'h2o' => 1250,
                    'h2' => 500,
                    'increase_rocket_drive' => 10,
                    'static_bonus' => 1,
                    'points' => 15,
                    'initial_researchtime' => 2750,
                ]
            ]);
            DB::table('research')->insert([
                // Waffensysteme
                [
                    'research_name' => 'weaponsystems',
                    'fe' => 2500,
                    'lut' => 1000,
                    'cry' => 0,
                    'h2o' => 0,
                    'h2' => 350,
                    'increase_ship_attack' => 10,
                    'static_bonus' => 1,
                    'points' => 7,
                    'initial_researchtime' => 300,
                ]
            ]);
            DB::table('research')->insert([
                // Panzerung
                [
                    'research_name' => 'armor',
                    'fe' => 12500,
                    'lut' => 2500,
                    'cry' => 0,
                    'h2o' => 0,
                    'h2' => 0,
                    'increase_ship_defense' => 10,
                    'static_bonus' => 1,
                    'points' => 3,
                    'initial_researchtime' => 720,
                ]
            ]);
            DB::table('research')->insert([
                // Schildsysteme
                [
                    'research_name' => 'shields',
                    'fe' => 3500,
                    'lut' => 22500,
                    'cry' => 10,
                    'h2o' => 500,
                    'h2' => 3750,
                    'increase_shield_defense' => 10,
                    'static_bonus' => 1,
                    'points' => 10,
                    'initial_researchtime' => 1200,
                ]
            ]);
            DB::table('research')->insert([
                // Radartechnologie
                [
                    'research_name' => 'radar',
                    'fe' => 17500,
                    'lut' => 12000,
                    'cry' => 0,
                    'h2o' => 0,
                    'h2' => 0,
                    'increase_counter_spy' => 10,
                    'static_bonus' => 1,
                    'points' => 5,
                    'initial_researchtime' => 3600,
                ]
            ]);
            DB::table('research')->insert([
                // Erw. Ladekapazität
                [
                    'research_name' => 'adv_cargo',
                    'fe' => 500,
                    'lut' => 500,
                    'cry' => 0,
                    'h2o' => 0,
                    'h2' => 0,
                    'increase_cargo' => 10,
                    'static_bonus' => 1,
                    'points' => 5,
                    'initial_researchtime' => 1200,
                ]
            ]);
            DB::table('research')->insert([
                // Ionisation
                [
                    'research_name' => 'ionisation',
                    'fe' => 7500,
                    'lut' => 5000,
                    'cry' => 0,
                    'h2o' => 750,
                    'h2' => 50,
                    'increase_ship_attack' => 5,
                    'static_bonus' => 1,
                    'points' => 3,
                    'initial_researchtime' => 5400,
                ]
            ]);
            DB::table('research')->insert([
                // Energiebündelung
                [
                    'research_name' => 'energy_concentration',
                    'fe' => 5000,
                    'lut' => 7500,
                    'cry' => 0,
                    'h2o' => 0,
                    'h2' => 1000,
                    'increase_ship_attack' => 7,
                    'static_bonus' => 1,
                    'points' => 7,
                    'initial_researchtime' => 2750,
                ]
            ]);
            DB::table('research')->insert([
                // Schiffsrumpf
                [
                    'research_name' => 'ship_hull',
                    'fe' => 1250,
                    'lut' => 750,
                    'cry' => 0,
                    'h2o' => 0,
                    'h2' => 0,
                    'increase_ship_defense' => 10,
                    'static_bonus' => 1,
                    'points' => 1,
                    'initial_researchtime' => 720,
                ]
            ]);
            DB::table('research')->insert([
                // Reichsadministration
                [
                    'research_name' => 'empire_administration',
                    'fe' => 25000,
                    'lut' => 12500,
                    'cry' => 0,
                    'h2o' => 7500,
                    'h2' => 3750,
                    'increase_max_planets' => 1,
                    'static_bonus' => 1,
                    'points' => 50,
                    'initial_researchtime' => 86400,
                ]
            ]);
            DB::table('research')->insert([
                // Kristallzucht
                [
                    'research_name' => 'crystal_farming',
                    'fe' => 5000,
                    'lut' => 37500,
                    'cry' => 0,
                    'h2o' => 2500,
                    'h2' => 5000,
                    'static_bonus' => 0,
                    'points' => 12,
                    'initial_researchtime' => 43200,
                ]
            ]);
            DB::table('research')->insert([
                // Antriebstechnologien
                [
                    'research_name' => 'engines',
                    'fe' => 500,
                    'lut' => 375,
                    'cry' => 0,
                    'h2o' => 250,
                    'h2' => 50,
                    'static_bonus' => 0,
                    'points' => 7,
                    'initial_researchtime' => 1200,
                ]
            ]);
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
