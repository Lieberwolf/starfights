<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Planet as Planet;

class UpdateMaxStoreValueForAllUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $planets = \App\Models\Planet::all();

        foreach ($planets as $planet) {
            PLanet::where('id', $planet->id)
                ->update([
                    'max_fe' => DB::raw("
                        (
                            select *
                            from ( select if(
                                (
                                    select sum(  (b.store_fe * i.level)) +10000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                    (
                                    select sum(  (b.store_fe * i.level)) +10000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                10000
                                )as fe
                            )as max_fe
                        )
                    "),
                    'max_lut' => DB::raw("
                        (
                           select *
                            from ( select if(
                                (
                                    select sum(  (b.store_lut * i.level)) +10000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                    (
                                    select sum(  (b.store_lut * i.level)) +10000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                10000
                                )as lut
                            )as max_lut
                        )
                    "),
                    'max_cry' => DB::raw("
                        (
                            select *
                            from ( select if(
                                (
                                    select sum(  (b.store_cry * i.level)) +100
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                    (
                                    select sum(  (b.store_cry * i.level)) +100
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                100
                                )as cry
                            )as max_cry
                        )
                    "),
                    'max_h2o' => DB::raw("
                        (
                           select *
                            from ( select if(
                                (
                                    select sum(  (b.store_h2o * i.level)) +10000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                    (
                                    select sum(  (b.store_h2o * i.level)) +10000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                10000
                                )as h2o
                            )as max_h2o
                        )
                    "),
                    'max_h2' => DB::raw("
                        (
                           select *
                            from ( select if(
                                (
                                    select sum(  (b.store_h2 * i.level)) +1000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                    (
                                    select sum(  (b.store_h2 * i.level)) +1000
                                    from buildings as b
                                    right JOIN infrastructures as i
                                    ON i.building_id = b.id
                                    join planets as p
                                    ON i.planet_id = p.id
                                    where p.id = {$planet->id}
                                ),
                                10000
                                )as h2
                            )as max_h2
                        )
                    "),

                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
