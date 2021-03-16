<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateTurretsTableInsertBasicData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turrets', function (Blueprint $table) {
            $table->json('building_requirements')->nullable();
            $table->json('research_requirements')->nullable();
        });

        DB::table('turrets')->insert([
            [
                'turret_name' => 'Laserturm',
                'description' => 'Einfacher Laserturm zur Verteidigung gegen leichte Jäger',
                'attack' => 250,
                'defend' => 400,
                'fe' => 400,
                'lut' => 300,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 0,
                'building_requirements' => '[]',
                'research_requirements' => '[]',
            ],
            [
                'turret_name' => 'Plasmakanone',
                'description' => 'Eine Kanone die Plasma auf gegnerische Raumschiffe feuert.',
                'attack' => 2500,
                'defend' => 3750,
                'fe' => 7500,
                'lut' => 10000,
                'cry' => 0,
                'h2o' => 0,
                'h2' => 300,
                'building_requirements' => '[]',
                'research_requirements' => '[]',
            ],
            [
                'turret_name' => 'EMP-Werfer',
                'description' => 'Der EMP-Werfer erzeugt störende Impulse die die angreifenden Raumschiffe stören oder zerstören kann.',
                'attack' => 12000,
                'defend' => 10000,
                'fe' => 25000,
                'lut' => 22500,
                'cry' => 10,
                'h2o' => 0,
                'h2' => 750,
                'building_requirements' => '[]',
                'research_requirements' => '[]',
            ],
            [
                'turret_name' => 'Flarak',
                'description' => 'Eine tödliche Flugabwahr Raketenbasis die alles unter Dauerbeschuss nimmt was nicht freundlich gesinnt ist.',
                'attack' => 25000,
                'defend' => 25000,
                'fe' => 50000,
                'lut' => 35000,
                'cry' => 50,
                'h2o' => 0,
                'h2' => 2250,
                'building_requirements' => '[]',
                'research_requirements' => '[]',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turrets', function (Blueprint $table) {
            //
        });
    }
}
