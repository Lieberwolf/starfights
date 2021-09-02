<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Building extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getOneById($id)
    {
        //return Building::find($id);
        return Building::where('buildings.id', $id)
            ->leftJoin('resourcefactors AS rf', 'rf.building_id','=','buildings.id')
            ->leftJoin('buildtimefactors AS bf', 'bf.building_id','=','buildings.id')
            ->first([
                'buildings.*',
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
                'bf.factor_1',
                'bf.factor_2',
                'bf.factor_3'
            ]);
    }

    public static function getOneByNameWithData($planet_id, $building_name)
    {
        return DB::table('buildings AS b')
                 ->where('b.building_name', $building_name)
                 ->leftJoin('infrastructures AS i', function($join) use ($planet_id)
                 {
                     $join->on('i.building_id', '=', 'b.id');
                     $join->where('i.planet_id', '=', $planet_id);
                 })
                 ->first();
    }

    public static function updateOrCreateFactorsById($id, $data)
    {
        if(DB::table('buildtimefactors')->where('building_id', $id)->first())
        {
            DB::table('buildtimefactors')->where('building_id', $id)->update([
                'factor_1' => $data['factor_1'],
                'factor_2' => $data['factor_2'],
                'factor_3' => $data['factor_3']
            ]);
        } else {
            DB::table('buildtimefactors')->insert([
                'building_id' => $id,
                'factor_1' => $data['factor_1'],
                'factor_2' => $data['factor_2'],
                'factor_3' => $data['factor_3']
            ]);
        }

        if(DB::table('resourcefactors')->where('building_id', $id)->first())
        {
            DB::table('resourcefactors')->where('building_id', $id)->update([
                'fe_factor_1' => $data['fe_factor_1'],
                'fe_factor_2' => $data['fe_factor_2'],
                'fe_factor_3' => $data['fe_factor_3'],
                'lut_factor_1' => $data['lut_factor_1'],
                'lut_factor_2' => $data['lut_factor_2'],
                'lut_factor_3' => $data['lut_factor_3'],
                'cry_factor_1' => $data['cry_factor_1'],
                'cry_factor_2' => $data['cry_factor_2'],
                'cry_factor_3' => $data['cry_factor_3'],
                'h2o_factor_1' => $data['h2o_factor_1'],
                'h2o_factor_2' => $data['h2o_factor_2'],
                'h2o_factor_3' => $data['h2o_factor_3'],
                'h2_factor_1' => $data['h2_factor_1'],
                'h2_factor_2' => $data['h2_factor_2'],
                'h2_factor_3' => $data['h2_factor_3'],
            ]);
        } else {
            DB::table('resourcefactors')->insert([
                'building_id' => $id,
                'fe_factor_1' => $data['fe_factor_1'],
                'fe_factor_2' => $data['fe_factor_2'],
                'fe_factor_3' => $data['fe_factor_3'],
                'lut_factor_1' => $data['lut_factor_1'],
                'lut_factor_2' => $data['lut_factor_2'],
                'lut_factor_3' => $data['lut_factor_3'],
                'cry_factor_1' => $data['cry_factor_1'],
                'cry_factor_2' => $data['cry_factor_2'],
                'cry_factor_3' => $data['cry_factor_3'],
                'h2o_factor_1' => $data['h2o_factor_1'],
                'h2o_factor_2' => $data['h2o_factor_2'],
                'h2o_factor_3' => $data['h2o_factor_3'],
                'h2_factor_1' => $data['h2_factor_1'],
                'h2_factor_2' => $data['h2_factor_2'],
                'h2_factor_3' => $data['h2_factor_3'],
            ]);
        }
    }

    public static function getOneByName($name)
    {
        return Building::where('building_name', $name)->first();
    }

    public static function getAllBuildingsForPlanet($planet_id) {

        return $buildings = DB::table('infrastructures AS i')
            ->leftJoin('buildtimefactors AS btf', 'btf.building_id', '=', 'i.building_id')
            ->leftJoin('resourcefactors AS rf', 'rf.building_id', '=', 'i.building_id')
            ->leftJoin('buildings AS b', 'b.id', '=', 'i.building_id')
            ->where('i.planet_id', '=', $planet_id)->where('b.prod_fe', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.prod_lut', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.prod_cry', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.prod_h2o', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.prod_h2', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.store_lut', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.store_cry', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.store_h2o', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.store_h2', '>', 0)
            ->orWhere('i.planet_id', '=', $planet_id)->where('b.store_h2', '>', 0)
            ->orderBy('i.planet_id')
            ->get();
    }

    public static function getAllBuildingsForPlanets($planet_ids) {

        return $buildings = DB::table('infrastructures AS i')
            ->leftJoin('buildtimefactors AS btf', 'btf.building_id', '=', 'i.building_id')
            ->leftJoin('resourcefactors AS rf', 'rf.building_id', '=', 'i.building_id')
            ->leftJoin('buildings AS b', 'b.id', '=', 'i.building_id')
            ->whereIn('i.planet_id', $planet_ids)->where('b.prod_fe', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.prod_lut', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.prod_cry', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.prod_h2o', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.prod_h2', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.store_lut', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.store_cry', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.store_h2o', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.store_h2', '>', 0)
            ->orWhereIn('i.planet_id', $planet_ids)->where('b.store_h2', '>', 0)
            ->orderBy('i.planet_id')
            ->get();
    }

    public static function getAllAvailableBuildings($planet_id, $user_id, $buildings = false)
    {
        // get all buildings
        if(!$buildings)
        {
            $buildings = DB::table('buildings AS b')
                ->orderBy('b.id')
                ->leftJoin('buildtimefactors AS btf','btf.building_id','=','b.id')
                ->leftJoin('resourcefactors AS rf', 'rf.building_id','=', 'b.id')
                ->get([
                    'b.*',
                    'btf.factor_1',
                    'btf.factor_2',
                    'btf.factor_3',
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
        }

        // get all researches
        $researches = DB::table('research')->get();

        $knowledge = DB::table('knowledge')
                       ->where('user_id', $user_id)
                       ->get();
        $infrastructure = [];
        $techtree = [];
        foreach($researches as $key => $research)
        {
            $research->knowledge = false;
            if(count($knowledge) > 0)
            {
                foreach($knowledge as $keyB => $tech)
                {
                    if($research->id == $tech->research_id)
                    {
                        $research->knowledge = $tech;
                    }
                }
            }
            $researches[$key] = $research;
        }

        $infra = DB::table('infrastructures AS i')->where('i.planet_id', $planet_id)->get();

        // get infrastructure
        foreach($buildings as $key => $building)
        {
            $buildings[$key]->buildable = true;
            $buildings[$key]->infrastructure = null;
            $infrastructure[$building->building_name] = null;
            foreach($infra as $data) {
                if($building->id == $data->building_id) {
                    $buildings[$key]->infrastructure = $data;
                    $infrastructure[$building->building_name] = $data;
                }
            }


            foreach(json_decode($building->building_requirements) as $keyB => $req)
            {
                $techtree[$building->building_name][$keyB]['reqLevel'] = $req;
            }
        }

        $tempBuildings = new Collection();

        foreach($buildings as $key => $building) {
            foreach (json_decode($building->building_requirements) as $keyB => $req) {
                if ($req > 0) {
                    foreach ($buildings as $keyC => $compareItem) {
                        if ($compareItem->building_name == $keyB) {
                            if ($compareItem->infrastructure) {
                                if ($compareItem->infrastructure->level >= $req) {
                                    if ($buildings[$key]->buildable) {
                                        $buildings[$key]->buildable = true;
                                        $tempBuildings[$key] = $buildings[$key];
                                    }
                                } else {
                                    $buildings[$key]->buildable = false;
                                    unset($tempBuildings[$key]);
                                    break;
                                }
                            } else {
                                $buildings[$key]->buildable = false;
                                unset($tempBuildings[$key]);
                                break;
                            }
                        }
                    }
                } else {
                    if ($buildings[$key]->buildable) {
                        $buildings[$key]->buildable = true;
                        $tempBuildings[$key] = $buildings[$key];
                    }
                }
            }
        }

        foreach($tempBuildings as $key => $building)
        {
            foreach(json_decode($building->research_requirements) as $keyB => $req)
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
                                    if($building->buildable != false)
                                    {
                                        $building->buildable = true;
                                    }
                                } else {
                                    $building->buildable = false;
                                    unset($tempBuildings[$key]);
                                    break;
                                }
                            } else {
                                $building->buildable = false;
                                unset($tempBuildings[$key]);
                                break;
                            }
                        }
                    }
                } else {
                    if($building->buildable != false)
                    {
                        $building->buildable = true;
                    }
                }
            }
        }
        // return list
        return $tempBuildings;
    }

    public static function startBuilding($building, $planet)
    {
        $proof = DB::table('building_process')->where('planet_id','=', $planet)->get();

        if(count($proof) == 0)
        {
            if($building->dynamic_buildtime == 0)
            {
                $buildtime = $building->initial_buildtime;
            } else {
                $buildtime = $building->actual_buildtime;
            }
            $insert_queue = DB::table('building_process')->insert([
                'planet_id' => $planet,
                'building_id' => $building->id,
                'started_at' => date('Y-m-d H:i:s',time()),
                'finished_at' => date('Y-m-d H:i:s',time()+$buildtime)
            ]);

            return $insert_queue;
        } else {
            dd('building in process');
        }
    }

    public static function cancelBuilding($planet_id)
    {
        return DB::table('building_process')->where('planet_id', $planet_id)->delete();
    }
}
