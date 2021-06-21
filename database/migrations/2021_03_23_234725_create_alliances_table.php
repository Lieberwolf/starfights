<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alliances', function (Blueprint $table) {
            $table->id();
            $table->string('alliance_name')->nullable()->unique();
            $table->string('alliance_tag')->nullable()->unique();
            $table->json('alliance_logo')->nullable();
            $table->json('alliance_description')->nullable();
            $table->json('alliance_ranks')->nullable();
            $table->json('alliance_messages')->nullable();
            $table->unsignedBigInteger('founder_id')->nullable();

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
        Schema::dropIfExists('alliances');
    }
}
