<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurretsProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turrets_process', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planet_id');
            $table->unsignedBigInteger('turret_id');
            $table->integer('amount_left');
            $table->integer('buildtime_total');
            $table->integer('buildtime_single');
            $table->dateTime('started_at', 0);
            $table->dateTime('finished_at', 0);

            $table->index('planet_id');
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
        Schema::dropIfExists('turrets_process');
    }
}
