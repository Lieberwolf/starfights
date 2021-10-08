<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateShipsTableInsertBasicData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ships', function (Blueprint $table) {
            $table->boolean('invasion')->default(0);
            $table->boolean('delta_scan')->default(0);
            $table->boolean('colonization')->default(0);
            $table->integer('initial_buildtime')->default(0);

        });

        DB::table('ships')->insert([
            /*
			[
				'order' => ,
				'ship_name' => '',
				'speed' => ,
				'attack' => ,
				'defend' => ,
				'cargo' => ,
				'consumption' => ,
				'invasion' => ,
				'spy' => ,
				'stealth' => ,
				'delta_scan' => 0,
				'colonization' => 0,
				'fe' => ,
				'lut' => ,
				'cry' => ,
				'h2o' => ,
				'h2' => ,
                'initial_buildtime' =>
			],
			*/
            // Spionagesonde
            [
                'order' => 1,
                'ship_name' => 'Spionagesonde',
                'speed' => 9000000,
                'attack' => 0,
                'defend' => 0,
                'cargo' => 15,
                'consumption' => 1,
                'invasion' => 0,
                'spy' => 1,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 150,
                'lut' => 50,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 0,
                'initial_buildtime' => 1200
            ],
            // Warpsonde
            [
                'order' => 2,
                'ship_name' => 'Warpsonde',
                'speed' => 27000000,
                'attack' => 0,
                'defend' => 10,
                'cargo' => 50,
                'consumption' => 1,
                'invasion' => 0,
                'spy' => 1,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 500,
                'lut' => 1500,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 250,
                'initial_buildtime' => 1200
            ],
            // Delta Dancer
            [
                'order' => 3,
                'ship_name' => 'Delta Dancer',
                'speed' => 500,
                'attack' => 50,
                'defend' => 50,
                'cargo' => 150,
                'consumption' => 10,
                'invasion' => 0,
                'spy' => 1,
                'stealth' => 0,
                'delta_scan' => 1,
                'colonization' => 0,
                'fe' => 250,
                'lut' => 75,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 50,
                'initial_buildtime' => 1200
            ],
            // Crusader
            [
                'order' => 4,
                'ship_name' => 'Crusader',
                'speed' => 650,
                'attack' => 100,
                'defend' => 700,
                'cargo' => 200,
                'consumption' => 20,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 500,
                'lut' => 250,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 0,
                'initial_buildtime' => 1200
            ],
            // Sternenjäger
            [
                'order' => 5,
                'ship_name' => 'Sternenjäger',
                'speed' => 850,
                'attack' => 400,
                'defend' => 200,
                'cargo' => 150,
                'consumption' => 25,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 400,
                'lut' => 300,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 0,
                'initial_buildtime' => 1200
            ],
            // Tarnbomber
            [
                'order' => 6,
                'ship_name' => 'Tarnbomber',
                'speed' => 2500,
                'attack' => 1000,
                'defend' => 150,
                'cargo' => 450,
                'consumption' => 100,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 1,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 2000,
                'lut' => 800,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 350,
                'initial_buildtime' => 1200
            ],
            // Kolonisationsschiff
            [
                'order' => 7,
                'ship_name' => 'Kolonisationsschiff',
                'speed' => 10000,
                'attack' => 10,
                'defend' => 500,
                'cargo' => 10000,
                'consumption' => 800,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 1,
                'fe' => 10000,
                'lut' => 15000,
                'cry' => 0,
                'h2o' => 2500,
                'h2' => 10000,
                'initial_buildtime' => 1200
            ],
            // Kleines Handelsschiff
            [
                'order' => 8,
                'ship_name' => 'Kleines Handelsschiff',
                'speed' => 1900,
                'attack' => 0,
                'defend' => 0,
                'cargo' => 9000,
                'consumption' => 10,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 4000,
                'lut' => 2000,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 0,
                'initial_buildtime' => 1200
            ],
            // Großes Handelsschiff
            [
                'order' => 9,
                'ship_name' => 'Großes Handelsschiff',
                'speed' => 55000,
                'attack' => 200,
                'defend' => 500,
                'cargo' => 100000,
                'consumption' => 30,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 16000,
                'lut' => 2000,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 3500,
                'initial_buildtime' => 1200
            ],
            // Akira
            [
                'order' => 10,
                'ship_name' => 'Akira',
                'speed' => 10000,
                'attack' => 11500,
                'defend' => 5000,
                'cargo' => 2500,
                'consumption' => 40,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 22500,
                'lut' => 17500,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 5000,
                'initial_buildtime' => 1200
            ],
            // Cobra
            [
                'order' => 11,
                'ship_name' => 'Cobra',
                'speed' => 8500,
                'attack' => 6500,
                'defend' => 13500,
                'cargo' => 5000,
                'consumption' => 55,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 25000,
                'lut' => 10000,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 3500,
                'initial_buildtime' => 1200
            ],
            // Pegasus
            [
                'order' => 12,
                'ship_name' => 'Pegasus',
                'speed' => 25000,
                'attack' => 25000,
                'defend' => 10000,
                'cargo' => 15000,
                'consumption' => 60,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 50000,
                'lut' => 65000,
                'cry' => 10,
                'h2o' => 0,
                'h2' => 5000,
                'initial_buildtime' => 1200
            ],
            // Phoenix
            [
                'order' => 13,
                'ship_name' => 'Phoenix',
                'speed' => 20000,
                'attack' => 18500,
                'defend' => 24500,
                'cargo' => 12500,
                'consumption' => 65,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 60000,
                'lut' => 30000,
                'cry' => 25,
                'h2o' => 0,
                'h2' => 7500,
                'initial_buildtime' => 1200
            ],
            // Aurora
            [
                'order' => 14,
                'ship_name' => 'Aurora',
                'speed' => 5000,
                'attack' => 1500,
                'defend' => 300,
                'cargo' => 50,
                'consumption' => 15,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 12000,
                'lut' => 4500,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 1500,
                'initial_buildtime' => 1200
            ],
            // Lavi
            [
                'order' => 15,
                'ship_name' => 'Lavi',
                'speed' => 4500,
                'attack' => 475,
                'defend' => 2100,
                'cargo' => 65,
                'consumption' => 35,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 7500,
                'lut' => 9500,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 2000,
                'initial_buildtime' => 1200
            ],
            // Moskito
            [
                'order' => 16,
                'ship_name' => 'Moskito',
                'speed' => 47500,
                'attack' => 17500,
                'defend' => 5000,
                'cargo' => 3500,
                'consumption' => 30,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 15000,
                'lut' => 45000,
                'cry' => 50,
                'h2o' => 1500,
                'h2' => 10000,
                'initial_buildtime' => 1200
            ],
            // Vega
            [
                'order' => 17,
                'ship_name' => 'Vega',
                'speed' => 22500,
                'attack' => 20000,
                'defend' => 20000,
                'cargo' => 11000,
                'consumption' => 100,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 1,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 16500,
                'lut' => 22000,
                'cry' => 120,
                'h2o' => 5000,
                'h2' => 20000,
                'initial_buildtime' => 1200
            ],
            // Black Dragon
            [
                'order' => 18,
                'ship_name' => 'Black Dragon',
                'speed' => 20000,
                'attack' => 100000,
                'defend' => 125000,
                'cargo' => 1000000,
                'consumption' => 250,
                'invasion' => 0,
                'spy' => 0,
                'stealth' => 0,
                'delta_scan' => 0,
                'colonization' => 0,
                'fe' => 100000,
                'lut' => 85000,
                'cry' => 1000,
                'h2o' => 50000,
                'h2' => 37500,
                'initial_buildtime' => 1200
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ships', function (Blueprint $table) {
            //
        });
    }
}
