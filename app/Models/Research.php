<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Research extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getOneById($id)
    {
        return Research::find($id);
    }

    public static function getOneByName($name)
    {
        return Research::where('research_name', $name)->first();
    }

    public static function getAllAvailableResearches($user_id, $planet_id)
    {
        // get all researches
        $researches = DB::table('research AS r')
            ->orderBy('r.id')
            ->leftJoin('researchtimefactors AS rtf','rtf.research_id','=','r.id')
            ->leftJoin('resourcefactors AS rf', 'rf.research_id','=', 'r.id')
            ->get([
                'r.*',
                'rtf.factor_1',
                'rtf.factor_2',
                'rtf.factor_3',
                'rf.fe_factor_1',
                'rf.fe_factor_2',
                'rf.fe_factor_3',
                'rf.lut_factor_1',
                'rf.lut_factor_2',
                'rf.lut_factor_3',
                'rf.cry_factor_1',
                'rf.cry_factor_2',
                'rf.cry_factor_3',
                'rf.h2o_factor_1',
                'rf.h2o_factor_2',
                'rf.h2o_factor_3',
                'rf.h2_factor_1',
                'rf.h2_factor_2',
                'rf.h2_factor_3',
            ]);
        // get all buildings
        $buildings = DB::table('buildings')
                       ->get();
        $knowledge = [];
        $techtree = [];

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

        foreach($researches as $key => $research)
        {
            foreach(json_decode($research->research_requirements) as $keyB => $req)
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
                                    if($researches[$key]->buildable != false)
                                    {
                                        $researches[$key]->buildable = true;
                                    }
                                } else {
                                    $researches[$key]->buildable = false;
                                }
                            } else {
                                $researches[$key]->buildable = false;
                            }
                        }
                    }
                } else {
                    if($researches[$key]->buildable != false)
                    {
                        $researches[$key]->buildable = true;
                    }
                }
            }

            foreach(json_decode($research->building_requirements) as $keyB => $req)
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
                                    if($researches[$key]->buildable != false)
                                    {
                                        $researches[$key]->buildable = true;
                                    }
                                } else {
                                    $researches[$key]->buildable = false;
                                }
                            } else {
                                $researches[$key]->buildable = false;
                            }
                        }
                    }
                } else {
                    if($researches[$key]->buildable != false)
                    {
                        $researches[$key]->buildable = true;
                    }
                }
            }
        }
        // return list
        return $researches;
    }

    public static function startResearch($research, $planet)
    {
        $proof = DB::table('research_process')->where('planet_id', $planet)->get();
        if(count($proof) == 0)
        {
            $researchtime = $research->actual_buildtime;

            $insert_queue = DB::table('research_process')->insert([
                'planet_id' => $planet,
                'research_id' => $research->id,
                'started_at' => date('Y-m-d H:i:s',time()),
                'finished_at' => date('Y-m-d H:i:s',time()+$researchtime)
            ]);

            return $insert_queue;
        } else {
            dd('building in process');
        }
    }

    public static function cancelResearch($planet_id)
    {
        return DB::table('research_process')->where('planet_id', $planet_id)->delete();
    }

    public static function getAllUserResearchPointsByUserId($user_id)
    {
        $points = 0;
        $researches = DB::table('knowledge AS k')
                       ->where('k.user_id','=', $user_id)
                       ->leftJoin('research AS r', 'k.research_id', '=', 'r.id')
                       ->get();

        if($researches)
        {
            foreach($researches as $key => $research)
            {
                $points += ($research->level * $research->points);
            }
        }
        return $points;
    }

    public static function getAllResearchesWithEffect()
    {
        return DB::table('research')->where(function ($query) {
            $query->where('increase_ship_attack', '>', 0)->orWhere('increase_ship_defense', '>', 0)->orWhere('increase_shield_defense', '>', 0);
        })->get();
    }

    public static function getUsersKnowledge($user_id)
    {
        return DB::table('knowledge')->where('user_id', $user_id)->get();
    }

    public static function getResearchProcesses($planet_ids)
    {
        $list = [];
        foreach($planet_ids as $planet_id)
        {
            $temp = DB::table('research_process')->where('planet_id', $planet_id->id)->first();
            if($temp)
            {
                $list[] = $temp;
            }
        }

        return $list;
    }
}
