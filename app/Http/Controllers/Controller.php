<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Planet as Planet;
use App\Models\Messages as Messages;
use App\Models\Building as Building;
use App\Models\Research as Research;
use App\Models\Fleet as Fleet;
use App\Models\Ship as Ship;
use Ramsey\Uuid\Uuid;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getAllUserPlanets($user_id)
    {
        return Planet::getAllUserPlanets($user_id);
    }

    public function getAllUserPlanetsWithData($user_id)
    {
        $planets = Planet::getAllUserPlanets($user_id);

        foreach($planets as $key => $planet)
        {
            $planets[$key]->data = Planet::getOneById($planet->id);
        }

        return $planets;
    }

    public function checkBuildingProcesses($planet_ids)
    {
        $user_id = Auth::id();
        foreach($planet_ids as $planet_id)
        {
            $process = DB::table('building_process AS bp')
                         ->where('planet_id', $planet_id->id)
                         ->first();

            if($process)
            {
                if(strtotime($process->finished_at) < now()->timestamp)
                {
                    $infrastructure = DB::table('infrastructures')
                                        ->where('planet_id', $planet_id->id)
                                        ->where('building_id', $process->building_id)
                                        ->first();

                    if(!$infrastructure)
                    {
                        // first build of this type
                        $levelUp = DB::table('infrastructures')
                                     ->insert([
                                         'planet_id' => $planet_id->id,
                                         'building_id' => $process->building_id,
                                         'level' => 1
                                     ]);
                    } else {
                        // at least lvl 1
                        $levelUp = DB::table('infrastructures')
                                     ->where('planet_id', $planet_id->id)
                                     ->where('building_id', $process->building_id)
                                     ->update(['level' => ($infrastructure->level + 1)]);
                    }

                    if($levelUp)
                    {
                        $cleanBuildProcesses = DB::table('building_process')
                                                 ->where('planet_id', $planet_id->id)
                                                 ->delete();

                        if($cleanBuildProcesses)
                        {
                            // pick last needed Info
                            $buildingData = Building::find($process->building_id);
                            $factors = DB::table('productionfactors')->where('planet_id', $planet_id->id)->first();
                            $planetData = Planet::find($planet_id->id);

                            if($buildingData->prod_fe > 0){
                                if($factors == null)
                                {
                                    $factors = new \stdClass();
                                    $factors->fe_factor_1 = 1.1000;
                                    $factors->fe_factor_2 = 1.7500;
                                    $factors->fe_factor_3 = 0.2300;
                                }
                                $base = $buildingData->prod_fe;
                                $h2o_cost = 0;
                                $lvl = $infrastructure ? $infrastructure->level + 1 : 1;
                                $Modifikator1 = ($lvl / $factors->fe_factor_1) + $factors->fe_factor_2;
                                $Modifikator2 = $lvl * $factors->fe_factor_3;
                                $rate =  $base * $Modifikator1 * $Modifikator2;

                                $planetData->rate_fe = $rate;
                                $planetData->rate_h2o = $h2o_cost;
                                $planetData->save();
                            }
                            if($buildingData->prod_lut > 0){
                                if($factors == null)
                                {
                                    $factors = new \stdClass();
                                    $factors->lut_factor_1 = 1.1000;
                                    $factors->lut_factor_2 = 1.7500;
                                    $factors->lut_factor_3 = 0.2300;
                                }
                                $base = $buildingData->prod_lut;
                                $h2o_cost = 0;
                                $lvl = $infrastructure ? $infrastructure->level + 1 : 1;
                                $Modifikator1 = ($lvl / $factors->lut_factor_1) + $factors->lut_factor_2;
                                $Modifikator2 = $lvl * $factors->lut_factor_3;
                                $rate =  $base * $Modifikator1 * $Modifikator2;

                                $planetData->rate_lut = $rate;
                                $planetData->rate_h2o = $h2o_cost;
                                $planetData->save();
                            }
                            if($buildingData->prod_cry > 0){
                                if($factors == null)
                                {
                                    $factors = new \stdClass();
                                    $factors->cry_factor_1 = 1.1000;
                                    $factors->cry_factor_2 = 1.7500;
                                    $factors->cry_factor_3 = 0.2300;
                                }
                                $base = $buildingData->prod_cry;
                                $h2o_cost = 0;
                                $lvl = $infrastructure ? $infrastructure->level + 1 : 1;
                                $Modifikator1 = ($lvl / $factors->cry_factor_1) + $factors->cry_factor_2;
                                $Modifikator2 = $lvl * $factors->cry_factor_3;
                                $rate =  $base * $Modifikator1 * $Modifikator2;

                                $planetData->rate_cry = $rate;
                                $planetData->rate_h2o = $h2o_cost;
                                $planetData->save();
                            }
                            if($buildingData->prod_h2o > 0){
                                if($factors == null)
                                {
                                    $factors = new \stdClass();
                                    $factors->h2o_factor_1 = 1.1000;
                                    $factors->h2o_factor_2 = 1.7500;
                                    $factors->h2o_factor_3 = 0.2300;
                                }
                                $base = $buildingData->prod_h2o;
                                $lvl = $infrastructure ? $infrastructure->level + 1 : 1;
                                $Modifikator1 = ($lvl / $factors->h2o_factor_1) + $factors->h2o_factor_2;
                                $Modifikator2 = $lvl * $factors->h2o_factor_3;
                                $rate =  $base * $Modifikator1 * $Modifikator2;

                                $planetData->rate_h2o = $rate;
                                $planetData->save();
                            }
                            if($buildingData->prod_h2 > 0){
                                if($factors == null)
                                {
                                    $factors = new \stdClass();
                                    $factors->h2_factor_1 = 1.1000;
                                    $factors->h2_factor_2 = 1.7500;
                                    $factors->h2_factor_3 = 0.2300;
                                }

                                $base = $buildingData->prod_h2;
                                $h2o_cost = $buildingData->cost_h2o;
                                $lvl = $infrastructure ? $infrastructure->level : 1;
                                $Modifikator1 = ($lvl / $factors->h2_factor_1) + $factors->h2_factor_2;
                                $Modifikator2 = $lvl * $factors->h2_factor_3;
                                $oldRate =  $base * $Modifikator1 * $Modifikator2;

                                $lvl = $infrastructure ? $infrastructure->level + 1 : 1;
                                $Modifikator1 = ($lvl / $factors->h2_factor_1) + $factors->h2_factor_2;
                                $Modifikator2 = $lvl * $factors->h2_factor_3;
                                $rate =  $base * $Modifikator1 * $Modifikator2;
                                $h2o_cost = $h2o_cost * $Modifikator1 * $Modifikator2;

                                $planetData->rate_h2 += $rate - $oldRate;
                                $planetData->rate_h2o -= $h2o_cost;
                                $planetData->save();
                            }



                            // emit system message to user
                            $message = [
                                'user_id' => 0,
                                'receiver_id' => $user_id,
                                'subject' => 'Konstruktion Abgeschlossen',
                                'message' => 'Konstruktion von '. $buildingData->building_name .' (Stufe ' . ($infrastructure ? $infrastructure->level + 1 : 1) . ') auf ' . $planetData->galaxy .':'. $planetData->system . ':' . $planetData->planet . ' wurde erfolgreich abgeschlossen.'
                            ];
                            Messages::create($message);
                        }
                    }
                }
            }
        }
    }

    public function checkResearchProcesses($planet_ids)
    {
        $user_id = Auth::id();
        foreach($planet_ids as $planet_id)
        {
            $process = DB::table('research_process')
                         ->where('planet_id', $planet_id->id)
                         ->first();
            if($process)
            {
                if(strtotime($process->finished_at) < now()->timestamp)
                {
                    $knowledge = DB::table('knowledge')
                                        ->where('user_id', $user_id)
                                        ->where('research_id', $process->research_id)
                                        ->first();

                    if(!$knowledge)
                    {
                        // first research of this type
                        $levelUp = DB::table('knowledge')
                                     ->insert([
                                         'user_id' => $user_id,
                                         'research_id' => $process->research_id,
                                         'level' => 1
                                     ]);
                    } else {
                        // at least lvl 1
                        $levelUp = DB::table('knowledge')
                                     ->where('user_id', $user_id)
                                     ->where('research_id', $process->research_id)
                                     ->update(['level' => ($knowledge->level + 1)]);

                    }

                    if($levelUp)
                    {
                        $cleanResearchProcesses = DB::table('research_process')
                                                 ->where('planet_id', $planet_id->id)
                                                 ->delete();

                        if($cleanResearchProcesses)
                        {
                            // pick last needed Info
                            $researchData = Research::find($process->research_id);
                            $planetData = Planet::find($planet_id->id);

                            // emit system message to user
                            $message = [
                                'user_id' => 0,
                                'receiver_id' => $user_id,
                                'subject' => 'Forschung Abgeschlossen',
                                'message' => 'Forschung von '. $researchData->research_name .' (Stufe ' . ($knowledge ? $knowledge->level + 1 : 1) . ') auf ' . $planetData->galaxy .':'. $planetData->system . ':' . $planetData->planet . ' wurde erfolgreich abgeschlossen.'
                            ];
                            Messages::create($message);
                        }
                    }
                }
            }
        }
    }

    public function checkShipProcesses($planet_ids)
    {
        $idList = [];
        foreach($planet_ids as $planet)
        {
            $idList[] = $planet->id;
        }

        $processes = DB::table('ships_process AS sp')
                         ->whereIn('sp.planet_id', $idList)
                         ->get();

        $buildTimes = [];
        foreach($processes as $key => $process)
        {
            if($process)
            {
                if(strtotime($process->finished_at) < now()->timestamp)
                {
                    self::addShipsToFleet($process->planet_id, $process->ship_id, $process->amount_left);
                    Fleet::setEmptyProcessForPlanet($process->planet_id);
                } else {
                    if($process->planet_id)
                    // not finished, how long will it take to finish the NEXT ship?
                    //how many time is gone since last reload?
                    $diff = now()->timestamp - strtotime($process->started_at);
                    $done = floor($diff / $process->buildtime_single);
                    if($done > 0)
                    {
                        self::addShipsToFleet($process->planet_id, $process->ship_id, $done);
                        $process->amount_left -= $done;
                        $process->started_at = strtotime($process->started_at) + ($done * $process->buildtime_single);
                        $process->buildtime_total -= ($done * $process->buildtime_single);
                        DB::table('ships_process AS sp')
                          ->where('sp.planet_id', $process->planet_id)->update([
                                'amount_left' => $process->amount_left,
                                'started_at' => date('Y-m-d H:i:s',$process->started_at)
                            ]);
                        $next = strtotime($process->started_at) + $process->buildtime_single - now()->timestamp;
                        // readable buildtime for FE
                        $timestamp =  $next;
                        $suffix = ':';

                        $days = floor(($timestamp / (24*60*60)));
                        $hours = ($timestamp / (60*60)) % 24;
                        $minutes = ($timestamp / 60) % 60;
                        $seconds = ($timestamp / 1) % 60;

                        if($days > 0)
                        {
                            $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
                        } else {
                            $days = '';
                        }

                        $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
                        $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
                        $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

                        $bauzeit = $days . $hours . $minutes . $seconds;
                        $temp = new \stdClass();
                        $temp->buildtime = $bauzeit;
                        $temp->planet = $process->planet_id;
                        $buildTimes[] = $temp;
                    } else {
                        $next = strtotime($process->started_at) + $process->buildtime_single - now()->timestamp;
                        // readable buildtime for FE
                        $timestamp =  $next;
                        $suffix = ':';

                        $days = floor(($timestamp / (24*60*60)));
                        $hours = ($timestamp / (60*60)) % 24;
                        $minutes = ($timestamp / 60) % 60;
                        $seconds = ($timestamp / 1) % 60;

                        if($days > 0)
                        {
                            $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
                        } else {
                            $days = '';
                        }

                        $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
                        $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
                        $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

                        $bauzeit = $days . $hours . $minutes . $seconds;
                        $temp = new \stdClass();
                        $temp->buildtime = $bauzeit;
                        $temp->planet = $process->planet_id;
                        $buildTimes[] = $temp;
                    }
                }
            }
        }
        return $buildTimes;
    }

    public function checkFleetProcesses($planet_ids)
    {
        $allfleets = Fleet::getFleetsOnMission($planet_ids);
        if($allfleets)
        {
            foreach($allfleets as $fleets)
            {
                foreach($fleets as $fleet)
                {
                    if(strtotime($fleet->arrival) <= now()->timestamp && $fleet->arrived == false)
                    {
                        // ships are at target
                        switch ($fleet->mission)
                        {
                            case 1:
                                $target = Planet::getOneById($fleet->target);

                                $targetShips = Fleet::getShipsAtPlanet($fleet->target);
                                $targetFleet = json_decode($targetShips->ship_types);
                                // optional resource transport
                                if($fleet->cargo != null) {
                                    $resources = json_decode($fleet->cargo);

                                    $target->fe += $resources->fe;
                                    $target->lut += $resources->lut;
                                    $target->cry += $resources->cry;
                                    $target->h2o += $resources->h2o;
                                    $target->h2 += $resources->h2;
                                    $target->save();
                                }

                                foreach(json_decode($fleet->ship_types) as $fleetShipType)
                                {
                                    foreach($targetFleet as $key => $targetShipType)
                                    {
                                        if($targetShipType->ship_id == $fleetShipType->ship_id)
                                        {
                                            $targetFleet[$key]->amount += $fleetShipType->amount;
                                        }
                                    }
                                }

                                $targetShips->ship_types = json_encode($targetFleet);
                                $targetShips->save();

                                // process done write message
                                // emit system message to user
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => Auth::id(),
                                    'subject' => 'Stationierung',
                                    'message' => 'Flotte von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' wurde auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' stationiert.',
                                ];
                                Messages::create($message);
                                $fleet->delete();

                                break;
                            case 2:
                                if($fleet->cargo != null)
                                {
                                    $resources = json_decode($fleet->cargo);

                                    $target = Planet::getOneById($fleet->target);
                                    $target->fe += $resources->fe;
                                    $target->lut += $resources->lut;
                                    $target->cry += $resources->cry;
                                    $target->h2o += $resources->h2o;
                                    $target->h2 += $resources->h2;
                                    $target->save();

                                    $resourcesList = $resources->fe > 0 ? 'Eisen: ' . $resources->fe . '<br/>' : '';
                                    $resourcesList .= $resources->lut > 0 ? 'Lutinum: ' . $resources->lut . '<br/>' : '';
                                    $resourcesList .= $resources->cry > 0 ? 'Kristalle: ' . $resources->cry . '<br/>' : '';
                                    $resourcesList .= $resources->h2o > 0 ? 'Wasser: ' . $resources->h2o . '<br/>' : '';
                                    $resourcesList .= $resources->h2 > 0 ? 'Wasserstoff: ' . $resources->h2 . '<br/>' : '';

                                    // emit system message to user
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => Auth::id(),
                                        'subject' => 'Transportbericht',
                                        'message' => 'Flotte von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' lieferte an ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' folgende Rohstoffe:<br/>' . $resourcesList,
                                    ];
                                    Messages::create($message);

                                    unset($fleet->readableSource);
                                    unset($fleet->readableTarget);
                                    $fleet->cargo = null;
                                    $fleet->save();
                                }
                                break;
                            case 3:
                                $research = Research::all();
                                $attackerResearch = Research::getUsersKnowledge(Auth::id());
                                $attackerSpyIncrements = [];
                                $defenderResearch = Research::getUsersKnowledge($fleet->readableTarget->user_id);
                                $defenderCounterSpyIncrements = [];

                                foreach($attackerResearch as $key => $attacker_research)
                                {
                                    foreach($research as $keyB => $originalResearch)
                                    {
                                        if($attacker_research->research_id == $originalResearch->id && $originalResearch->increase_spy > 0)
                                        {
                                            $originalResearch->level = $attacker_research->level;
                                            $attackerSpyIncrements[] = $originalResearch;
                                        }
                                    }
                                }

                                foreach($defenderResearch as $key => $defender_research)
                                {
                                    foreach($research as $keyB => $originalResearch)
                                    {
                                        if($defender_research->research_id == $originalResearch->id && $originalResearch->increase_counter_spy > 0)
                                        {
                                            $originalResearch->level = $defender_research->level;
                                            $defenderCounterSpyIncrements[] = $originalResearch;
                                        }
                                    }
                                }


                                //dd($defenderCounterSpyIncrements);
                                // drones die, so delete fleet
                                //dd('die');
                                $fleet->delete();
                                break;
                            case 4:
                                dd($fleet);
                                break;
                            case 5:
                                $shipTypes = json_decode($fleet->ship_types);
                                foreach($shipTypes as $key => $shipType) {
                                    if($shipType->ship_name == "Kolonisationsschiff")
                                    {
                                        $shipTypes[$key]->amount -= 1;
                                    }
                                }
                                $fleet->ship_types = json_encode($shipTypes);

                                if($fleet->readableTarget->user_id == null)
                                {
                                    // emit system message to user
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => Auth::id(),
                                        'subject' => 'Kolonisierung',
                                        'message' => 'Der Planet ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' wurde erfolgreich kolonisiert.',
                                    ];
                                    Messages::create($message);

                                    unset($fleet->readableSource);
                                    unset($fleet->readableTarget);

                                    $fleet->planet_id = $fleet->target;
                                    $fleet->target = null;
                                    $fleet->mission = null;
                                    $fleet->arrival = null;
                                    $fleet->departure = null;

                                    Planet::where('id', $fleet->planet->id)->update([
                                        'user_id' => Auth::id(),
                                        'fe' => 500,
                                        'lut' => 500,
                                        'h2o' => 500,
                                        'rate_fe' => 10,
                                        'rate_lut' => 10,
                                        'rate_cry' => 0,
                                        'rate_h2o' => 10,
                                        'rate_h2' => 0,
                                    ]);
                                    $fleet->save();
                                    return true;
                                } else {
                                    $fleet->delete();
                                    dd('error while colonization');
                                }
                                break;
                            case 6:

                                $allResearchForFight = Research::getAllResearchesWithEffect();
                                $attacker["ship"] = json_decode($fleet->ship_types);
                                $attacker["home"] = Planet::getOneById($fleet->planet_id);
                                $attacker["research"] = Research::getUsersKnowledge($attacker["home"]->user_id);

                                // get defenders ship process
                                $targetPlanet = Planet::getOneById($fleet->target);
                                $planet_ids = Planet::getAllUserPlanets($targetPlanet->user_id);
                                self::checkShipProcesses($planet_ids);

                                $defender = Fleet::getShipsAtPlanet($fleet->target);
                                if($defender) {
                                    $defender["ship"] = json_decode($defender->ship_types);
                                } else {
                                    $defender["ship"] = false;
                                }
                                $defender["home"] = Planet::getOneById($fleet->target);
                                $defender["research"] = Research::getUsersKnowledge($defender["home"]->user_id);

                                $attacker["attack_value"] = 0;
                                $attacker["final_attack_value"] = 0;
                                $attacker["final_defense_value"] = 0;
                                $attacker["defense_value"] = 0;
                                $attacker["final_shield_value"] = 0;
                                $cargo = 0;

                                $defender["attack_value"] = 0;
                                $defender["final_attack_value"] = 0;
                                $defender["final_defense_value"] = 0;
                                $defender["defense_value"] = 0;
                                $defender["final_shield_value"] = 0;

                                $attackerList = [];
                                foreach($attacker["ship"] as $key => $ship)
                                {
                                    // check if ship was selected
                                    if($ship->amount > 0)
                                    {
                                        $attackerList[$key] = new \stdClass();
                                        $attackerList[$key] = Ship::getOneById($ship->ship_id);
                                        $attackerList[$key]->amount = $ship->amount;
                                        $attacker["attack_value"] += $attackerList[$key]->amount * $attackerList[$key]->attack;
                                        $attacker["defense_value"] += $attackerList[$key]->amount * $attackerList[$key]->defend;
                                        $cargo += $ship->amount * $attackerList[$key]->cargo;
                                    }
                                }
/*
                                foreach($allResearchForFight as $research)
                                {
                                    foreach($attacker["research"] as $key => $attackerResearch)
                                    {
                                        if($research->id == $attackerResearch->research_id)
                                        {
                                            $attacker["research"][$key] = $research;
                                            $attacker["research"][$key]->level = $attackerResearch->level;
                                        }
                                    }
                                }
*/
$attacker["research"] = [];
                                // calc final values for attack
                                if(count($attacker["research"]) > 0)
                                {
                                    foreach($attacker["research"] as $research)
                                    {
                                        if(property_exists($research, 'increase_ship_attack') && $research->increase_ship_attack > 0)
                                        {
                                            $temp = 0;
                                            for($i = 0; $i < $research->level; $i++)
                                            {
                                                $temp += ($attacker["attack_value"] + $temp) * ($research->increase_ship_attack / 100);
                                            }
                                            $attacker["final_attack_value"] = $temp;
                                        }

                                        if(property_exists($research, 'increase_ship_defense') && $research->increase_ship_defense > 0)
                                        {
                                            $temp = 0;
                                            for($i = 0; $i < $research->level; $i++)
                                            {
                                                $temp += ($attacker["defense_value"] + $temp) * ($research->increase_ship_defense / 100);
                                            }
                                            $attacker["final_defense_value"] = $temp;
                                        }

                                        if(property_exists($research, 'increase_shield_defense') && $research->increase_shield_defense > 0)
                                        {
                                            $temp = 0;
                                            for($i = 0; $i < $research->level; $i++)
                                            {
                                                $temp += ($attacker["defense_value"] + $temp) * ($research->increase_shield_defense / 100);
                                            }
                                            $attacker["final_shield_value"] = $temp;
                                        }
                                    }
                                }
                                $attacker["final_attack_value"] += $attacker["attack_value"];
                                $attacker["final_defense_value"] += $attacker["defense_value"] + $attacker["final_shield_value"];

                                $attacker["final_attack_value"] = floor($attacker["final_attack_value"]);
                                $attacker["final_defense_value"] = floor($attacker["final_defense_value"]);

                                /////////// defender Part

                                $defenderList = [];
                                if($defender["ship"]) {
                                    foreach($defender["ship"] as $key => $ship)
                                    {
                                        // check if ship was selected
                                        if($ship->amount > 0)
                                        {
                                            $defenderList[$key] = new \stdClass();
                                            $defenderList[$key] = Ship::getOneById($ship->ship_id);
                                            $defenderList[$key]->amount = $ship->amount;
                                            $defender["attack_value"] += $defenderList[$key]->amount * $defenderList[$key]->attack;
                                            $defender["defense_value"] += $defenderList[$key]->amount * $defenderList[$key]->defend;
                                        }
                                    }
                                }
                                $defenderResearchList = [];
                                foreach($allResearchForFight as $research)
                                {
                                    foreach($defender["research"] as $key => $defenderResearch)
                                    {
                                        if($research->id == $defenderResearch->research_id)
                                        {

                                            $temp = $research;
                                            $temp->level = $defenderResearch->level;
                                            $defenderResearchList[] = $temp;
                                        }
                                    }
                                }

                                // calc final values for attack
                                if(count($defenderResearchList) > 0)
                                {
                                    foreach($defenderResearchList as $research)
                                    {
                                        if(property_exists($research, 'increase_ship_attack') && $research->increase_ship_attack > 0)
                                        {
                                            $temp = 0;
                                            for($i = 0; $i < $research->level; $i++)
                                            {
                                                $temp += ($defender["attack_value"] + $temp) * ($research->increase_ship_attack / 100);
                                            }
                                            $defender["final_attack_value"] = $temp;
                                        }

                                        if(property_exists($research, 'increase_ship_defense') && $research->increase_ship_defense > 0)
                                        {
                                            $temp = 0;
                                            for($i = 0; $i < $research->level; $i++)
                                            {
                                                $temp += ($defender["defense_value"] + $temp) * ($research->increase_ship_defense / 100);
                                            }
                                            $defender["final_defense_value"] = $temp;
                                        }

                                        if(property_exists($research, 'increase_shield_defense') && $research->increase_shield_defense > 0)
                                        {
                                            $temp = 0;
                                            for($i = 0; $i < $research->level; $i++)
                                            {
                                                $temp += ($defender["defense_value"] + $temp) * ($research->increase_shield_defense / 100);
                                            }
                                            $defender["final_shield_value"] = $temp;
                                        }
                                    }
                                }
                                $defender["final_attack_value"] += $defender["attack_value"];
                                $defender["final_defense_value"] += $defender["defense_value"] + $defender["final_shield_value"];

                                $defender["final_attack_value"] = floor($defender["final_attack_value"]);
                                $defender["final_defense_value"] = floor($defender["final_defense_value"]);


                                $survivedDef = $defender["final_defense_value"] - $attacker["final_attack_value"];
                                if($defender["final_defense_value"] > 0 && $survivedDef > 0)
                                {
                                    $survivedDefRatio = 100 / $defender["final_defense_value"] * $survivedDef;
                                } else {
                                    $survivedDefRatio = 0;
                                }
                                $defender["survivedDefRatio"] = $survivedDefRatio;

                                $survivedAtt = $attacker["final_defense_value"] - $defender["final_attack_value"];
                                if($attacker["final_defense_value"] > 0 && $survivedAtt > 0)
                                {
                                    $survivedAttRatio = 100 / $attacker["final_defense_value"] * $survivedAtt;
                                } else {
                                    $survivedAttRatio = 0;
                                }
                                $attacker["survivedAttRatio"] = $survivedAttRatio;
                                $attacker["hasSurvived"] = false;
                                foreach($attacker["ship"] as $key => $attackerShip)
                                {
                                    $attackerShip->newAmount = ceil($attackerShip->amount * ($survivedAttRatio/100));
                                    if($attackerShip->newAmount > 0 && $attacker["hasSurvived"] != true)
                                    {
                                        $attacker["hasSurvived"] = true;
                                    }
                                }

                                if($defender["ship"]) {
                                    foreach($defender["ship"] as $key => $defenderShip)
                                    {
                                        $defenderShip->newAmount = ceil($defenderShip->amount * ($survivedDefRatio/100));
                                    }
                                }

                                // attacker can return? collect resources
                                if($attacker["hasSurvived"])
                                {
                                    $attackerList = [];
                                    foreach($attacker["ship"] as $key => $attackerShip)
                                    {
                                        $temp = new \stdClass();
                                        $temp->ship_id = $attackerShip->ship_id;
                                        $temp->ship_name = $attackerShip->ship_name;
                                        $temp->amount = $attackerShip->newAmount;
                                        $attackerList[] = $temp;
                                    }
                                    $temp = $fleet;
                                    $cargoFe = $cargo * .45;
                                    $cargoLut = $cargo * .35;
                                    $cargoCry = $cargo * .05;
                                    $cargoH2o = $cargo * .05;
                                    $cargoH2 = $cargo * .1;

                                    if($defender["home"]->fe < $cargoFe)
                                    {
                                        $cargoFe = floor($defender["home"]->fe);
                                    }
                                    $defender["home"]->fe -= $cargoFe;

                                    if($defender["home"]->lut < $cargoLut)
                                    {
                                        $cargoLut = floor($defender["home"]->lut);
                                    }
                                    $defender["home"]->lut -= $cargoLut;

                                    if($defender["home"]->cry < $cargoCry)
                                    {
                                        $cargoCry = floor($defender["home"]->cry);
                                    }
                                    $defender["home"]->cry -= $cargoCry;

                                    if($defender["home"]->h2o < $cargoH2o)
                                    {
                                        $cargoH2o = floor($defender["home"]->h2o);
                                    }
                                    $defender["home"]->h2o -= $cargoH2o;

                                    if($defender["home"]->h2 < $cargoH2)
                                    {
                                        $cargoH2 = floor($defender["home"]->h2);
                                    }
                                    $defender["home"]->h2 -= $cargoH2;

                                    $resourceJson = [
                                        "fe" => $cargoFe,
                                        "lut" => $cargoLut,
                                        "cry" => $cargoCry,
                                        "h2o" => $cargoH2o,
                                        "h2" => $cargoH2,
                                    ];

                                    $defender["home"]->save();
                                    $temp->ship_types = json_encode($attackerList);
                                    $temp->cargo = json_encode($resourceJson);
                                }


                                // create report
                                $report = Report::create([
                                    'link' => Uuid::uuid4(),
                                    'attacker_id' => $fleet->readableSource->user_id,
                                    'defender_id' => $fleet->readableTarget->user_id,
                                    'attacker_fleet' => json_encode($attacker["ship"]),
                                    'defender_fleet' => json_encode($defender["ship"]),
                                    'defender_defense' => null,
                                    'resources' => $temp->cargo,
                                    'attacker_planet_id' => $fleet->readableSource->id,
                                    'defender_planet_id' => $fleet->readableTarget->id,
                                ]);

                                $link = Report::where('id', $report->id)->first();
                                // emit system message to users
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $fleet->readableSource->user_id,
                                    'subject' => 'Kampfbericht',
                                    'message' => 'Flotte von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' griff ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' an (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $fleet->readableTarget->user_id,
                                    'subject' => 'Kampfbericht',
                                    'message' => 'Flotte von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' griff ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' an (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);

                                // set attacker fleet
                                if($attacker["hasSurvived"])
                                {
                                    $temp->arrived = true;
                                    unset($temp->readableSource);
                                    unset($temp->readableTarget);
                                    $temp->save();
                                } else {
                                    $fleet->delete();
                                }

                                // set defender fleet
                                $defenderList = [];
                                if($defender["ship"]) {
                                    foreach ( $defender["ship"] as $key => $defenderShip ) {
                                        $temp            = new \stdClass();
                                        $temp->ship_id   = $defenderShip->ship_id;
                                        $temp->ship_name = $defenderShip->ship_name;
                                        $temp->amount    = $defenderShip->newAmount;
                                        $defenderList[]  = $temp;
                                    }
                                    $defender->ship_types = json_encode( $defenderList );

                                    unset( $defender["ship"] );
                                    unset( $defender["home"] );
                                    unset( $defender["research"] );
                                    unset( $defender["attack_value"] );
                                    unset( $defender["final_attack_value"] );
                                    unset( $defender["final_defense_value"] );
                                    unset( $defender["defense_value"] );
                                    unset( $defender["final_shield_value"] );
                                    unset( $defender["survivedDefRatio"] );
                                    $defender->save();
                                }
                                break;
                            case 7:
                                dd($fleet);
                                break;
                        }
                    }

                    // fleet is also back (except stationierung, spionage && kolonisierung)
                    if(strtotime($fleet->arrival) + (strtotime($fleet->arrival) - strtotime($fleet->departure)) <= now()->timestamp && $fleet->mission != 1 && $fleet->mission != 3 && $fleet->mission != 5)
                    {
                        // fleet is back
                        $homeFleet = Fleet::getShipsAtPlanet($fleet->planet_id);
                        $homePlanet = Planet::getOneById($fleet->planet_id);
                        $home_fleet_type = json_decode($homeFleet->ship_types);
                        $fleet_type = json_decode($fleet->ship_types);
                        $cargo = json_decode($fleet->cargo);
                        foreach($home_fleet_type as $key => $ship)
                        {
                            //dd($ship->ship_id);
                            foreach($fleet_type as $keyB => $fleetShip)
                            {
                                if($fleetShip->ship_id == $ship->ship_id)
                                {
                                    $home_fleet_type[$key]->amount += $fleetShip->amount;
                                }
                            }
                        }

                        if($cargo != null) {
                            $homePlanet->fe += $cargo->fe;
                            $homePlanet->lut += $cargo->lut;
                            $homePlanet->cry += $cargo->cry;
                            $homePlanet->h2o += $cargo->h2o;
                            $homePlanet->h2 += $cargo->h2;
                            $homePlanet->save();
                        }

                        $homeFleet->ship_types = json_encode($home_fleet_type);
                        $homeFleet->save();

                        // emit system message to user
                        $message = [
                            'user_id' => 0,
                            'receiver_id' => Auth::id(),
                            'subject' => 'Flottenaktivität',
                            'message' => 'Eine Flotte kehrt zurück',
                        ];
                        Messages::create($message);
                        $fleet->delete();
                    }
                }
            }
        }
    }

    private static function addShipsToFleet($planet_id, $ship_id, $amount)
    {
        // ship is done, add it to planetary fleet
        $fleet = Fleet::getShipsAtPlanet($planet_id);
        if($fleet)
        {
            // add to existing fleet
            $fleet_types = json_decode($fleet->ship_types);
            foreach($fleet_types as $key => $ship_type)
            {
                if($ship_type->ship_id == $ship_id)
                {
                    $fleet_types[$key]->amount += $amount;
                }
            }

            $fleet->ship_types = json_encode($fleet_types);
            $fleet->save();
            return true;

        } else {
            // add new fleet to planet
            $proof = Fleet::setFleetForPlanet($planet_id, $ship_id, $amount);
            if($proof != false)
            {
                return true;
            }
        }
    }
}
