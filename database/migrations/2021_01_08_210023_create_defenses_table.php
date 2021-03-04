<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planet_id');
            $table->text('description');
            $table->integer('laser')->nullable();
            $table->integer('plasma')->nullable();
            $table->integer('emp')->nullable();
            $table->integer('flak')->nullable();

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
        Schema::dropIfExists('defenses');
    }
}
