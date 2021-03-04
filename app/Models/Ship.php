<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ship extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getOneById($id)
    {
        return Ship::find($id);
    }

    public static function getOneByName($name)
    {
        return Ship::where('ship_name', $name)->first();
    }

    public static function getAllAvailableShips($user_id, $planet_id)
    {
        // get all researches
        $researches = DB::table('research')
                        ->get();
        // get all buildings
        $buildings = DB::table('buildings')
                       ->get();
        // get all Ships
        $ships = DB::table('ships')
                   ->get();

        foreach($ships as $key => $ship)
        {
            $ship->buildable = true;
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

        foreach($ships as $key => $ship)
        {
            foreach(json_decode($ship->research_requirements) as $keyB => $req)
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
                                    if($ships[$key]->buildable != false)
                                    {
                                        $ships[$key]->buildable = true;
                                    }
                                } else {
                                    $ships[$key]->buildable = false;
                                }
                            } else {
                                $ships[$key]->buildable = false;
                            }
                        }
                    }
                } else {
                    if($ships[$key]->buildable != false)
                    {
                        $ships[$key]->buildable = true;
                    }
                }
            }

            foreach(json_decode($ship->building_requirements) as $keyB => $req)
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
                                    if($ships[$key]->buildable != false)
                                    {
                                        $ships[$key]->buildable = true;
                                    }
                                } else {
                                    $ships[$key]->buildable = false;
                                }
                            } else {
                                $ships[$key]->buildable = false;
                            }
                        }
                    }
                } else {
                    if($ships[$key]->buildable != false)
                    {
                        $ships[$key]->buildable = true;
                    }
                }
            }
        }
        // return list
        foreach($ships as $key => $ship)
        {
            if($ship->buildable == false)
            {
                unset($ships[$key]);
            }
        }
        return $ships;
    }

    public static function setProductionProcess($planet_id, $shipAmount, $available_ship)
    {
        $totalBuildtime = $available_ship->current_buildtime * $shipAmount;

        return DB::table('ships_process')->insert([
            'planet_id' => $planet_id,
            'ship_id' => $available_ship->id,
            'amount_left' => $shipAmount,
            'buildtime_total' => $totalBuildtime,
            'buildtime_single' => $available_ship->current_buildtime,
            'started_at' => date('Y-m-d H:i:s',now()->timestamp),
            'finished_at' => date('Y-m-d H:i:s',now()->timestamp + $totalBuildtime),
        ]);
    }

}
