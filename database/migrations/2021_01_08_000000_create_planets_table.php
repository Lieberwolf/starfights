<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planets', function (Blueprint $table) {
            $table->id();
            $table->integer('galaxy');
            $table->integer('system');
            $table->integer('planet');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('planet_name')->nullable();
            $table->string('image')->nullable();
            $table->integer('diameter');
            $table->integer('resource_bonus');
            $table->integer('temperature');
            $table->boolean('atmosphere');
            $table->float('fe')->nullable();
            $table->float('lut')->nullable();
            $table->float('cry')->nullable();
            $table->float('h2o')->nullable();
            $table->float('h2')->nullable();
            $table->float('rate_fe')->nullable();
            $table->float('rate_lut')->nullable();
            $table->float('rate_cry')->nullable();
            $table->float('rate_h2o')->nullable();
            $table->float('rate_h2')->nullable();

            $table->index('user_id');
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
        Schema::dropIfExists('planets');
    }
}
