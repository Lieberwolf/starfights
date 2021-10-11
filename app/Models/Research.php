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
            ->leftJoin('knowledge AS k', function($join) use ($user_id)
            {
                $join->on('k.research_id', '=', 'r.id')->where('k.user_id', '=', $user_id);
            })
            ->get([
                'k.*',
                'r.*',
                'rtf.*',
                'rf.*',
                'r.id AS research_id'
            ]);


        // get all buildings
        $buildings = DB::table('buildings AS b')
            ->leftJoin('infrastructures AS i', function($join) use ($planet_id)
            {
                $join->on('i.building_id', '=', 'b.id');
                $join->where('i.planet_id', '=', $planet_id);
            })
            ->get();

        $techtree = [];

        // get knowledge
        foreach($researches as $key => $research)
        {
            $researches[$key]->buildable = true;

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
                            if($compareItem->level)
                            {
                                if($compareItem->level >= $req)
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
                            if($compareItem->level)
                            {
                                if($compareItem->level >= $req)
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
                'research_id' => $research->research_id,
                'started_at' => date('Y-m-d H:i:s',time()),
                'finished_at' => date('Y-m-d H:i:s',time()+$researchtime)
            ]);

            return $insert_queue;
        } else {
            dd('research in process');
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
        })->get([
            '*',
            'id as research_id'
        ]);
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
