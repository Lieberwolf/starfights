<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStorageCountToPlanets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planets', function (Blueprint $table) {
            $table->bigInteger('max_fe')->default(10000);
            $table->bigInteger('max_lut')->default(10000);
            $table->bigInteger('max_cry')->default(100);
            $table->bigInteger('max_h2o')->default(10000);
            $table->bigInteger('max_h2')->default(1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planets', function (Blueprint $table) {
            $table->dropColumn('max_fe');
            $table->dropColumn('max_lut');
            $table->dropColumn('max_cry');
            $table->dropColumn('max_h2o');
            $table->dropColumn('max_h2');
        });
    }
}
