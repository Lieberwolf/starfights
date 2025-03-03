<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildtimefactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildtimefactors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id')->default(0.0001);
            $table->float('factor_1', 8, 4)->default(0.0001);
            $table->float('factor_2', 8, 4)->default(0.0001);
            $table->float('factor_3', 8, 4)->default(0.0001);

            $table->index('building_id');
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
        Schema::dropIfExists('buildtimefactors');
    }
}
