<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('ship_name')->unique();
            $table->text('description')->nullable();
            $table->integer('speed');
            $table->integer('attack')->nullable();
            $table->integer('defend')->nullable();
            $table->integer('cargo')->nullable();
            $table->integer('consumption');
            $table->boolean('spy');
            $table->boolean('stealth');
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
        Schema::dropIfExists('ships');
    }
}
