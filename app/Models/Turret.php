<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Turret extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getOneById($turret_id)
    {
        return Turret::find($turret_id);
    }

    public static function getOneByName($name)
    {
        return Turret::where('turret_name', $name)->first();
    }

    public static function getAllAvailableTurrets($user_id, $planet_id)
    {
        // get all researches
        $researches = DB::table('research')
                        ->get();
        // get all buildings
        $buildings = DB::table('buildings')
                       ->get();
        // get all Ships
        $turrets = DB::table('turrets')
                   ->get();

        foreach($turrets as $key => $turret)
        {
            $turret->buildable = true;
        }


        foreach($buildings as $key => $building)
        {
            $building->infrastructure = DB::table('infrastructures')
                                          ->where('building_id', $building->id)
                                          ->where('planet_id', $planet_id)
                                          ->first();

            $buildings[$key] = $building;
        }

        // get knowledge
        foreach($researches as $key => $research)
        {
            $temp = DB::table('knowledge AS i')
                      ->where('i.research_id', '=', $research->id)
                      ->where('i.user_id', '=', $user_id)
                      ->first();

            $researches[$key]->buildable = true;
            $researches[$key]->knowledge = $temp;
            $knowledge[$research->research_name] = $temp;

            foreach(json_decode($research->research_requirements) as $keyB => $req)
            {
                $techtree[$research->research_name][$keyB]['reqLevel'] = $req;
            }
        }

        foreach($turrets as $key => $turret)
        {
            foreach(json_decode($turret->research_requirements) as $keyB => $req)
            {
                if($req > 0)
                {
                    foreach($researches as $keyC => $compareItem)
                    {
                        if($compareItem->research_name == $keyB)
                        {
                            if($compareItem->knowledge)
                            {
                                if($compareItem->knowledge->level >= $req)
                                {
                                    if($turrets[$key]->buildable != false)
                                    {
                                        $turrets[$key]->buildable = true;
                                    }
                                } else {
                                    $turrets[$key]->buildable = false;
                                }
                            } else {
                                $turrets[$key]->buildable = false;
                            }
                        }
                    }
                } else {
                    if($turrets[$key]->buildable != false)
                    {
                        $turrets[$key]->buildable = true;
                    }
                }
            }

            foreach(json_decode($turret->building_requirements) as $keyB => $req)
            {
                if($req > 0)
                {
                    foreach($buildings as $keyC => $compareItem)
                    {
                        if($compareItem->building_name == $keyB)
                        {
                            if($compareItem->infrastructure)
                            {
                                if($compareItem->infrastructure->level >= $req)
                                {
                                    if($turrets[$key]->buildable != false)
                                    {
                                        $turrets[$key]->buildable = true;
                                    }
                                } else {
                                    $turrets[$key]->buildable = false;
                                }
                            } else {
                                $turrets[$key]->buildable = false;
                            }
                        }
                    }
                } else {
                    if($turrets[$key]->buildable != false)
                    {
                        $turrets[$key]->buildable = true;
                    }
                }
            }
        }
        // return list
        foreach($turrets as $key => $turret)
        {
            if($turret->buildable == false)
            {
                unset($turrets[$key]);
            }
        }

        return $turrets;
    }

    public static function setProductionProcess($planet_id, $turretAmount, $available_turret)
    {
        $totalBuildtime = $available_turret->current_buildtime * $turretAmount;

        return DB::table('turrets_process')->insert([
            'planet_id' => $planet_id,
            'turret_id' => $available_turret->id,
            'amount_left' => $turretAmount,
            'buildtime_total' => $totalBuildtime,
            'buildtime_single' => $available_turret->current_buildtime,
            'started_at' => date('Y-m-d H:i:s',now()->timestamp),
            'finished_at' => date('Y-m-d H:i:s',now()->timestamp + $totalBuildtime),
        ]);
    }

}
