<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->uuid('link');
            $table->unsignedBigInteger('attacker_id');
            $table->unsignedBigInteger('defender_id')->nullable()->default(0);
            $table->json('attacker_fleet')->nullable();
            $table->json('defender_fleet')->nullable();
            $table->json('defender_defense')->nullable();
            $table->json('resources')->nullable();
            $table->json('planet_info')->nullable()->default(null);
            $table->json('planet_infrastructure')->nullable()->default(null);
            $table->json('defender_knowledge')->nullable()->default(null);

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
        Schema::dropIfExists('reports');
    }
}
