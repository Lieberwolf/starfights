<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionfactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productionfactors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planet_id')->nullable();
            $table->float('fe_factor_1', 8, 4)->default(0.0001);
            $table->float('fe_factor_2', 8, 4)->default(0.0001);
            $table->float('fe_factor_3', 8, 4)->default(0.0001);
            $table->float('lut_factor_1', 8, 4)->default(0.0001);
            $table->float('lut_factor_2', 8, 4)->default(0.0001);
            $table->float('lut_factor_3', 8, 4)->default(0.0001);
            $table->float('cry_factor_1', 8, 4)->default(0.0001);
            $table->float('cry_factor_2', 8, 4)->default(0.0001);
            $table->float('cry_factor_3', 8, 4)->default(0.0001);
            $table->float('h2o_factor_1', 8, 4)->default(0.0001);
            $table->float('h2o_factor_2', 8, 4)->default(0.0001);
            $table->float('h2o_factor_3', 8, 4)->default(0.0001);
            $table->float('h2_factor_1', 8, 4)->default(0.0001);
            $table->float('h2_factor_2', 8, 4)->default(0.0001);
            $table->float('h2_factor_3', 8, 4)->default(0.0001);

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
        Schema::dropIfExists('productionfactors');
    }
}
