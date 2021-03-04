<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->string('race_name');
            $table->string('description');
            $table->boolean('building_bonus');
            $table->integer('building_bonus_value');
            $table->boolean('research_bonus');
            $table->integer('research_bonus_value');
            $table->boolean('attack_bonus');
            $table->integer('attack_bonus_value');
            $table->boolean('defend_bonus');
            $table->integer('defend_bonus_value');
            $table->boolean('resource_bonus');
            $table->integer('resource_bonus_value');
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
        Schema::dropIfExists('races');
    }
}
