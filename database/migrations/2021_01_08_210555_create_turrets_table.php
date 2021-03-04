<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurretsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turrets', function (Blueprint $table) {
            $table->integer('order');
            $table->string('turret_name')->unique();
            $table->text('description')->nullable();
            $table->integer('attack')->nullable();
            $table->integer('defend');
            $table->integer('fe')->nullable();
            $table->integer('lut')->nullable();
            $table->integer('cry')->nullable();
            $table->integer('h2o')->nullable();
            $table->integer('h2')->nullable();
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
        Schema::dropIfExists('turrets');
    }
}
