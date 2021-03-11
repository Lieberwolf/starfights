<?php

namespace App\Http\Controllers;

use App\Models\Profile;
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
use phpDocumentor\Reflection\Types\Null_;
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
                    $infrastructure = Controller::getLevelForBuildingOnPlanet($planet_id->id, $process->building_id);

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
                            $planetData = Planet::find($planet_id->id);

                            self::calcResourceRatesForPlanet($planet_id->id);

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

    public static function calcResourceRatesForPlanet($planet_id)
    {
        $buildings = Building::all();

        $resourceRates = new \stdClass();
        $resourceRates->prod_fe = 0;
        $resourceRates->cost_fe = 0;
        $resourceRates->prod_lut = 0;
        $resourceRates->cost_lut = 0;
        $resourceRates->prod_cry = 0;
        $resourceRates->cost_cry = 0;
        $resourceRates->prod_h2o = 0;
        $resourceRates->cost_h2o = 0;
        $resourceRates->prod_h2 = 0;
        $resourceRates->cost_h2 = 0;

        foreach($buildings as $building)
        {
            $factors = DB::table('productionfactors')->where('building_id', $building->id)->first();
            $infrastructure = self::getLevelForBuildingOnPlanet($planet_id, $building->id);
            $building->infrastructure = $infrastructure;

            if($infrastructure) {
                if($building->prod_fe > 0){
                    if($factors == null)
                    {
                        $factors = new \stdClass();
                        $factors->fe_factor_1 = 1.1000;
                        $factors->fe_factor_2 = 1.7500;
                        $factors->fe_factor_3 = 0.2300;
                    }
                    $lvl = $infrastructure->level;
                    $base = $building->prod_fe;
                    $Modifikator1 = ($lvl / $factors->fe_factor_1) + $factors->fe_factor_2;
                    $Modifikator2 = $lvl * $factors->fe_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->prod_fe = $rate;
                    $resourceRates->prod_fe += $rate;
                }
                if($building->prod_lut > 0){
                    if($factors == null)
                    {
                        $factors = new \stdClass();
                        $factors->lut_factor_1 = 1.1000;
                        $factors->lut_factor_2 = 1.7500;
                        $factors->lut_factor_3 = 0.2300;
                    }
                    $lvl = $infrastructure->level;
                    $base = $building->prod_lut;
                    $Modifikator1 = ($lvl / $factors->lut_factor_1) + $factors->lut_factor_2;
                    $Modifikator2 = $lvl * $factors->lut_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->prod_lut = $rate;
                    $resourceRates->prod_lut += $rate;
                }
                if($building->prod_cry > 0){
                    if($factors == null)
                    {
                        $factors = new \stdClass();
                        $factors->cry_factor_1 = 1.1000;
                        $factors->cry_factor_2 = 1.7500;
                        $factors->cry_factor_3 = 0.2300;
                    }
                    $lvl = $infrastructure->level;
                    $base = $building->prod_cry;
                    $Modifikator1 = ($lvl / $factors->cry_factor_1) + $factors->cry_factor_2;
                    $Modifikator2 = $lvl * $factors->cry_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->prod_cry = $rate;
                    $resourceRates->prod_cry += $rate;

                    $base = $building->cost_lut;
                    $Modifikator1 = ($lvl / $factors->cry_factor_1) + $factors->cry_factor_2;
                    $Modifikator2 = $lvl * $factors->cry_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->cost_lut = $rate;
                    $resourceRates->cost_lut += $rate;

                    $base = $building->cost_h2;
                    $Modifikator1 = ($lvl / $factors->cry_factor_1) + $factors->cry_factor_2;
                    $Modifikator2 = $lvl * $factors->cry_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->cost_h2 = $rate;
                    $resourceRates->cost_h2 += $rate;
                }
                if($building->prod_h2o > 0){
                    if($factors == null)
                    {
                        $factors = new \stdClass();
                        $factors->h2o_factor_1 = 1.1000;
                        $factors->h2o_factor_2 = 1.7500;
                        $factors->h2o_factor_3 = 0.2300;
                    }
                    $lvl = $infrastructure->level;
                    $base = $building->prod_h2o;
                    $Modifikator1 = ($lvl / $factors->h2o_factor_1) + $factors->h2o_factor_2;
                    $Modifikator2 = $lvl * $factors->h2o_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->prod_h2o = $rate;
                    $resourceRates->prod_h2o += $rate;
                }
                if($building->prod_h2 > 0){
                    if($factors == null)
                    {
                        $factors = new \stdClass();
                        $factors->h2_factor_1 = 1.1000;
                        $factors->h2_factor_2 = 1.7500;
                        $factors->h2_factor_3 = 0.2300;
                    }

                    $lvl = $infrastructure->level;
                    $base = $building->prod_h2;
                    $Modifikator1 = ($lvl / $factors->h2_factor_1) + $factors->h2_factor_2;
                    $Modifikator2 = $lvl * $factors->h2_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->prod_h2 = $rate;
                    $resourceRates->prod_h2 += $rate;

                    $base = $building->cost_h2o;
                    $Modifikator1 = ($lvl / $factors->h2_factor_1) + $factors->h2_factor_2;
                    $Modifikator2 = $lvl * $factors->h2_factor_3;
                    $rate =  $base * $Modifikator1 * $Modifikator2;
                    $building->cost_h2o = $rate;
                    $resourceRates->cost_h2o += $rate;
                }
            }

        }

        $planet = Planet::getOneById($planet_id);

        $bonusValues = new \stdClass();
        $bonusValues->fe = $resourceRates->prod_fe / 100 * $planet->resource_bonus;
        $bonusValues->lut = $resourceRates->prod_lut / 100 * $planet->resource_bonus;
        $bonusValues->h2o = $resourceRates->prod_h2o / 100 * $planet->resource_bonus;

        $finalValueFe = ($resourceRates->prod_fe + $bonusValues->fe) - $resourceRates->cost_fe;
        $finalValueLut = ($resourceRates->prod_lut + $bonusValues->lut) - $resourceRates->cost_lut;
        $finalValueCry = $resourceRates->prod_cry - $resourceRates->cost_cry;
        $finalValueH2o = ($resourceRates->prod_h2o + $bonusValues->h2o) - $resourceRates->cost_h2o;
        $finalValueH2 = $resourceRates->prod_h2 - $resourceRates->cost_h2;

        // +10 for base production
        $planet->rate_fe = $finalValueFe + 10;
        $planet->rate_lut = $finalValueLut + 10;
        $planet->rate_cry = $finalValueCry;
        $planet->rate_h2o = $finalValueH2o + 10;
        $planet->rate_h2 = $finalValueH2;

        $planet->save();
        $return = [
            $buildings,
            $bonusValues
        ];

        return $return;
    }

    public static function getLevelForBuildingOnPlanet($planet_id, $building)
    {
        return DB::table('infrastructures')
          ->where('planet_id', $planet_id)
          ->where('building_id', $building)
          ->first();
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
                    $noReturn = false;

                    if(strtotime($fleet->arrival) <= now()->timestamp && $fleet->arrived == false)
                    {
                        // get defenders ship process
                        $targetPlanet = Planet::getOneById($fleet->target);
                        $planet_ids = Planet::getAllUserPlanets($targetPlanet->user_id);
                        self::checkShipProcesses($planet_ids);
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


                                if(count($defenderCounterSpyIncrements) == 0)
                                {
                                    $success = true;
                                } else {
                                    if(count($attackerSpyIncrements) == 0) {
                                        // defender auto wins, because attacker has no improvements (which never can happen cause a spy drone needs spionagetechnologie)
                                        $success = false;
                                    } else {
                                        $attackerValue = 1200;
                                        $defenderValue = 1200;
                                        foreach($attackerSpyIncrements as $increment)
                                        {
                                            for($i = 0; $i <= $increment->level; $i++)
                                            {
                                                $attackerValue += $attackerValue * ($increment->increase_spy/100);
                                            }
                                        }
                                        foreach($defenderCounterSpyIncrements as $increment)
                                        {
                                            for($i = 0; $i <= $increment->level; $i++)
                                            {
                                                $defenderValue += $defenderValue * ($increment->increase_counter_spy/100);
                                            }
                                        }
                                        if($attackerValue >= $defenderValue)
                                        {
                                            $success = true;
                                        } else {
                                            $success = false;
                                        }
                                    }
                                }

                                if($success)
                                {
                                    $defenderFleet = Fleet::getShipsAtPlanet($fleet->target);
                                    if($defenderFleet == null)
                                    {
                                        $defenderShips = null;
                                    } else {
                                        $defenderShips = $defenderFleet->ship_types;
                                    }
                                    $defenderResourcesRaw = Planet::getPlanetaryResourcesByPlanetId($fleet->target, $fleet->readableTarget->user_id);
                                    $defenderResources = $defenderResourcesRaw[0];

                                    $resourceJson = [
                                        "fe" => ceil($defenderResources[0]->fe),
                                        "lut" => ceil($defenderResources[0]->lut),
                                        "cry" => ceil($defenderResources[0]->cry),
                                        "h2o" => ceil($defenderResources[0]->h2o),
                                        "h2" => ceil($defenderResources[0]->h2),
                                    ];

                                    // create report
                                    $report = Report::create([
                                        'link' => Uuid::uuid4(),
                                        'attacker_id' => $fleet->readableSource->user_id,
                                        'defender_id' => $fleet->readableTarget->user_id,
                                        'attacker_fleet' => null,
                                        'defender_fleet' => $defenderShips,
                                        'defender_defense' => null,
                                        'resources' => json_encode($resourceJson),
                                        'attacker_planet_id' => $fleet->readableSource->id,
                                        'defender_planet_id' => $fleet->readableTarget->id,
                                        'report_type' => 2,
                                    ]);

                                    $link = Report::where('id', $report->id)->first();
                                    // emit system message to users
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $fleet->readableSource->user_id,
                                        'subject' => 'Spionagebericht',
                                        'message' => 'Spionagemission von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' erfolgte auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $fleet->readableTarget->user_id,
                                        'subject' => 'Spionagebericht',
                                        'message' => 'Spionagemission von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' erfolgte auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                } else {
                                    // create report
                                    $report = Report::create([
                                        'link' => Uuid::uuid4(),
                                        'attacker_id' => $fleet->readableSource->user_id,
                                        'defender_id' => $fleet->readableTarget->user_id,
                                        'attacker_fleet' => null,
                                        'defender_fleet' => null,
                                        'defender_defense' => null,
                                        'resources' => null,
                                        'attacker_planet_id' => $fleet->readableSource->id,
                                        'defender_planet_id' => $fleet->readableTarget->id,
                                        'report_type' => 0,
                                    ]);

                                    $link = Report::where('id', $report->id)->first();
                                    // emit system message to users
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $fleet->readableSource->user_id,
                                        'subject' => 'Spionagebericht',
                                        'message' => 'Spionagemission von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' schlug fehl. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $fleet->readableTarget->user_id,
                                        'subject' => 'Spionagebericht',
                                        'message' => 'Spionagemission von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' erfolgreich verhindert. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                }


                                // drones die, so delete fleet
                                $fleet->delete();
                                break;
                            case 4:
                                // is planet occupied by another user?
                                if($fleet->readableTarget->user_id != null)
                                {
                                    $research = Research::all();
                                    $attackerResearch = Research::getUsersKnowledge(Auth::id());
                                    $attackerSpyIncrements = [];
                                    $defenderResearch = Research::getUsersKnowledge($fleet->readableTarget->user_id);
                                    $defenderCounterSpyIncrements = [];
                                    $defenderFullResearchList = [];
                                    $defenderFullBuildingList = [];

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
                                            if($defender_research->research_id == $originalResearch->id)
                                            {
                                                $tempResearch = new \stdClass();
                                                $tempResearch->research_name = $originalResearch->research_name;
                                                $tempResearch->level = $defender_research->level;
                                                $defenderFullResearchList[] = $tempResearch;
                                            }
                                        }
                                    }


                                    if(count($defenderCounterSpyIncrements) == 0)
                                    {
                                        $success = true;
                                    } else {
                                        if(count($attackerSpyIncrements) == 0) {
                                            // defender auto wins, because attacker has no improvements (which never can happen cause a spy drone needs spionagetechnologie)
                                            $success = false;
                                        } else {
                                            $attackerValue = 1200;
                                            $defenderValue = 1200;
                                            foreach($attackerSpyIncrements as $increment)
                                            {
                                                for($i = 0; $i <= $increment->level; $i++)
                                                {
                                                    $attackerValue += $attackerValue * ($increment->increase_spy/100);
                                                }
                                            }
                                            foreach($defenderCounterSpyIncrements as $increment)
                                            {
                                                for($i = 0; $i <= $increment->level; $i++)
                                                {
                                                    $defenderValue += $defenderValue * ($increment->increase_counter_spy/100);
                                                }
                                            }
                                            if($attackerValue >= $defenderValue)
                                            {
                                                $success = true;
                                            } else {
                                                $success = false;
                                            }
                                        }
                                    }

                                    if($success)
                                    {
                                        $defenderFleet = Fleet::getShipsAtPlanet($fleet->target);
                                        if($defenderFleet == null)
                                        {
                                            $defenderShips = null;
                                        } else {
                                            $defenderShips = $defenderFleet->ship_types;
                                        }
                                        $defenderResourcesRaw = Planet::getPlanetaryResourcesByPlanetId($fleet->target, $fleet->readableTarget->user_id);
                                        $defenderResources = $defenderResourcesRaw[0];

                                        $resourceJson = [
                                            "fe" => ceil($defenderResources[0]->fe),
                                            "lut" => ceil($defenderResources[0]->lut),
                                            "cry" => ceil($defenderResources[0]->cry),
                                            "h2o" => ceil($defenderResources[0]->h2o),
                                            "h2" => ceil($defenderResources[0]->h2),
                                        ];

                                        $planetInfo = Planet::getOneById($fleet->target);

                                        $infoJson = [
                                            'diameter' => $planetInfo->diameter,
                                            'temperature' => $planetInfo->temperature,
                                            'atmosphere' => $planetInfo->atmosphere,
                                            'resource_bonus' => $planetInfo->resource_bonus,
                                        ];

                                        $allBuildings = Building::all();
                                        $rawInfrastructure = DB::table('infrastructures')->where('planet_id', $fleet->target)->get();

                                        foreach($allBuildings as $building)
                                        {
                                            foreach($rawInfrastructure as $infra)
                                            {
                                                if($infra->building_id == $building->id)
                                                {
                                                    $tempBuilding = new \stdClass();
                                                    $tempBuilding->building_name = $building->building_name;
                                                    $tempBuilding->level = $infra->level;
                                                    $defenderFullBuildingList[] = $tempBuilding;
                                                }
                                            }
                                        }

                                        // create report
                                        $report = Report::create([
                                            'link' => Uuid::uuid4(),
                                            'attacker_id' => $fleet->readableSource->user_id,
                                            'defender_id' => $fleet->readableTarget->user_id,
                                            'attacker_fleet' => null,
                                            'defender_fleet' => $defenderShips,
                                            'defender_defense' => null,
                                            'resources' => json_encode($resourceJson),
                                            'attacker_planet_id' => $fleet->readableSource->id,
                                            'defender_planet_id' => $fleet->readableTarget->id,
                                            'report_type' => 1,
                                            'planet_info' => json_encode($infoJson),
                                            'planet_infrastructure' => json_encode($defenderFullBuildingList),
                                            'defender_knowledge' => json_encode($defenderFullResearchList),
                                        ]);

                                        $link = Report::where('id', $report->id)->first();
                                        // emit system message to users
                                        $message = [
                                            'user_id' => 0,
                                            'receiver_id' => $fleet->readableSource->user_id,
                                            'subject' => 'Delta Scan',
                                            'message' => 'Delta Scan von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' erfolgte auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                        ];
                                        Messages::create($message);
                                        $message = [
                                            'user_id' => 0,
                                            'receiver_id' => $fleet->readableTarget->user_id,
                                            'subject' => 'Delta Scan',
                                            'message' => 'Delta Scan von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' erfolgte auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                        ];
                                        Messages::create($message);
                                    } else {
                                        // create report
                                        $report = Report::create([
                                            'link' => Uuid::uuid4(),
                                            'attacker_id' => $fleet->readableSource->user_id,
                                            'defender_id' => $fleet->readableTarget->user_id,
                                            'attacker_fleet' => null,
                                            'defender_fleet' => null,
                                            'defender_defense' => null,
                                            'resources' => null,
                                            'attacker_planet_id' => $fleet->readableSource->id,
                                            'defender_planet_id' => $fleet->readableTarget->id,
                                            'report_type' => 0,
                                        ]);

                                        $link = Report::where('id', $report->id)->first();
                                        // emit system message to users
                                        $message = [
                                            'user_id' => 0,
                                            'receiver_id' => $fleet->readableSource->user_id,
                                            'subject' => 'Delta Scan',
                                            'message' => 'Delta Scan von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' schlug fehl. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                        ];
                                        Messages::create($message);
                                        $message = [
                                            'user_id' => 0,
                                            'receiver_id' => $fleet->readableTarget->user_id,
                                            'subject' => 'Delta Scan',
                                            'message' => 'Delta Scan von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' erfolgreich verhindert. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                        ];
                                        Messages::create($message);
                                    }
                                } else {
                                    $planetInfo = Planet::getOneById($fleet->target);

                                    $infoJson = [
                                        'diameter' => $planetInfo->diameter,
                                        'temperature' => $planetInfo->temperature,
                                        'atmosphere' => $planetInfo->atmosphere,
                                        'resource_bonus' => $planetInfo->resource_bonus,
                                    ];

                                    // create report
                                    $report = Report::create([
                                        'link' => Uuid::uuid4(),
                                        'attacker_id' => $fleet->readableSource->user_id,
                                        'defender_id' => $fleet->readableTarget->user_id,
                                        'attacker_fleet' => null,
                                        'defender_fleet' => null,
                                        'defender_defense' => null,
                                        'resources' => null,
                                        'attacker_planet_id' => $fleet->readableSource->id,
                                        'defender_planet_id' => $fleet->readableTarget->id,
                                        'report_type' => 1,
                                        'planet_info' => json_encode($infoJson),
                                    ]);

                                    $link = Report::where('id', $report->id)->first();
                                    // emit system message to users
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $fleet->readableSource->user_id,
                                        'subject' => 'Delta Scan',
                                        'message' => 'Der Delta Scan von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' auf ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' war erfolgreich. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                }

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
                                $result = self::fightCalculation($fleet);

                                $fleet = $result->fleet;
                                $temp = $result->temp;
                                $attacker = $result->attacker;
                                $defender = $result->defender;

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
                                    'report_type' => 3,
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
                                $result = self::fightCalculation($fleet);

                                $fleet = $result->fleet;
                                $temp = $result->temp;
                                $attacker = $result->attacker;
                                $defender = $result->defender;
                                $defenderHome = Profile::where('user_id', $fleet->readableTarget->user_id)->first(['start_planet']);

                                if($attacker["survivedAttRatio"] > 92 && $defender["survivedDefRatio"] <= 0 && $defenderHome->start_planet != $fleet->readableTarget->id)
                                {

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
                                        'report_type' => 4,
                                    ]);

                                    $link = Report::where('id', $report->id)->first();
                                    // emit system message to users
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $fleet->readableSource->user_id,
                                        'subject' => 'Invasionsbericht',
                                        'message' => 'Flotte von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' eroberte ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $fleet->readableTarget->user_id,
                                        'subject' => 'Invasionsbericht',
                                        'message' => 'Flotte von ' . $fleet->readableSource->galaxy .':'. $fleet->readableSource->system . ':' . $fleet->readableSource->planet . ' eroberte ' . $fleet->readableTarget->galaxy .':'. $fleet->readableTarget->system . ':' . $fleet->readableTarget->planet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);

                                    // delete defender fleet if there was
                                    if($defender["ship"])
                                    {
                                        $defender->delete();
                                    }
                                    // rewrite user id for planet
                                    $targetPlanet->user_id = $fleet->readableSource->user_id;
                                    $targetPlanet->save();
                                    // save attacker fleet to new planet

                                    foreach($attacker["ship"] as $key => $attackerShip)
                                    {
                                        $tempShip = new \stdClass();
                                        $tempShip->ship_id = $attackerShip->ship_id;
                                        $tempShip->ship_name = $attackerShip->ship_name;
                                        $tempShip->amount = $attackerShip->newAmount;
                                        // invasion unit --1
                                        if($attackerShip->ship_id == 19)
                                        {
                                            $tempShip->amount--;
                                        }
                                        $attackerList[] = $tempShip;
                                    }

                                    $fleet->ship_types = json_encode($attackerList);
                                    $fleet->planet_id = $targetPlanet->id;
                                    $fleet->cargo = Null;
                                    $fleet->mission = Null;
                                    $fleet->target = Null;
                                    $fleet->arrival = Null;
                                    $fleet->departure = Null;
                                    unset($fleet->readableSource);
                                    unset($fleet->readableTarget);

                                    $noReturn = true;

                                    $fleet->save();
                                } else {
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
                                        'report_type' => 3,
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
                                        $temp->mission = 6;
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
                                }
                                break;
                        }
                    }

                    // fleet is also back (except stationierung, spionage && kolonisierung, invasion)
                    if(strtotime($fleet->arrival) + (strtotime($fleet->arrival) - strtotime($fleet->departure)) <= now()->timestamp && $fleet->mission != 1 && $fleet->mission != 3 && $fleet->mission != 5 && $fleet->mission != 7 && !$noReturn)
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

    public static function factorizeBuildings($buildingList)
    {
        $decreasers = [];

        foreach($buildingList as $key => $building)
        {

            // is building increasing buildtime?
            if($building->dynamic_buildtime)
            {
                $f1 = $building->factor_1 > 0 ? $building->factor_1 : 0.0001;
                $f2 = $building->factor_2 > 0 ? $building->factor_2 : 0.0001;
                $f3 = $building->factor_3 > 0 ? $building->factor_3 : 0.0001;

                $Grundzeit = $building->initial_buildtime;
                $Stufe = $building->infrastructure ? $building->infrastructure->level : 0;
                $Modifikator1 = ($Stufe / ($f1) ) + $f2;
                $Modifikator2 = $Stufe * $f3;
                $suffix = ':';

                if($Stufe == 0)
                {
                    $buildingList[$key]->actual_buildtime = $Grundzeit;
                } else {
                    $buildingList[$key]->actual_buildtime =  floor($Grundzeit * $Modifikator1 * $Modifikator2);
                }

                $days = floor(($buildingList[$key]->actual_buildtime / (24*60*60)));
                $hours = ($buildingList[$key]->actual_buildtime / (60*60)) % 24;
                $minutes = ($buildingList[$key]->actual_buildtime / 60) % 60;
                $seconds = ($buildingList[$key]->actual_buildtime / 1) % 60;

                if($days > 0)
                {
                    $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
                } else {
                    $days = '';
                }

                $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
                $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
                $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

                $buildingList[$key]->readableBuildtime = $days . $hours . $minutes . $seconds;
            } else {
                $suffix = ':';

                $days = floor(($buildingList[$key]->initial_buildtime / (24*60*60)));
                $hours = ($buildingList[$key]->initial_buildtime / (60*60)) % 24;
                $minutes = ($buildingList[$key]->initial_buildtime / 60) % 60;
                $seconds = ($buildingList[$key]->initial_buildtime / 1) % 60;

                if($days > 0)
                {
                    $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
                } else {
                    $days = '';
                }

                $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
                $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
                $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

                $buildingList[$key]->readableBuildtime = $days . $hours . $minutes . $seconds;
            }

            // calc resource cost
            if($building->infrastructure != null)
            {
                $f1 = $building->fe_factor_1 > 0 ? $building->fe_factor_1 : 0.0001;
                $f2 = $building->fe_factor_2 > 0 ? $building->fe_factor_2 : 0.0001;
                $f3 = $building->fe_factor_3 > 0 ? $building->fe_factor_3 : 0.0001;
                $cost = $building->fe;
                $level = $building->infrastructure ? $building->infrastructure->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $buildingList[$key]->fe =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $building->lut_factor_1 > 0 ? $building->lut_factor_1 : 0.0001;
                $f2 = $building->lut_factor_2 > 0 ? $building->lut_factor_2 : 0.0001;
                $f3 = $building->lut_factor_3 > 0 ? $building->lut_factor_3 : 0.0001;
                $cost = $building->lut;
                $level = $building->infrastructure ? $building->infrastructure->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $buildingList[$key]->lut =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $building->cry_factor_1 > 0 ? $building->cry_factor_1 : 0.0001;
                $f2 = $building->cry_factor_2 > 0 ? $building->cry_factor_2 : 0.0001;
                $f3 = $building->cry_factor_3 > 0 ? $building->cry_factor_3 : 0.0001;
                $cost = $building->cry;
                $level = $building->infrastructure ? $building->infrastructure->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $buildingList[$key]->cry =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $building->h2o_factor_1 > 0 ? $building->h2o_factor_1 : 0.0001;
                $f2 = $building->h2o_factor_2 > 0 ? $building->h2o_factor_2 : 0.0001;
                $f3 = $building->h2o_factor_3 > 0 ? $building->h2o_factor_3 : 0.0001;
                $cost = $building->h2o;
                $level = $building->infrastructure ? $building->infrastructure->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $buildingList[$key]->h2o =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $building->h2_factor_1 > 0 ? $building->h2_factor_1 : 0.0001;
                $f2 = $building->h2_factor_2 > 0 ? $building->h2_factor_2 : 0.0001;
                $f3 = $building->h2_factor_3 > 0 ? $building->h2_factor_3 : 0.0001;
                $cost = $building->h2;
                $level = $building->infrastructure ? $building->infrastructure->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $buildingList[$key]->h2 =  floor($cost * $Modifikator1 * $Modifikator2);
            }

            if($building->decrease_building_timeBy > 0 && $building->infrastructure != null)
            {
                $temp = new \stdClass();
                $temp->level = $building->infrastructure->level;
                $temp->factor = $building->decrease_building_timeBy;

                $decreasers[] = $temp;
            }
        }

        // apply buildtime bonusses
        foreach($buildingList as $key => $building)
        {
            if($building->dynamic_buildtime)
            {
                foreach($decreasers as $decreaser)
                {
                    for($i = 0; $i < $decreaser->level; $i++)
                    {
                        $building->actual_buildtime -= $building->actual_buildtime * ($decreaser->factor / 100);
                    }
                }
                $days = floor(($buildingList[$key]->actual_buildtime / (24*60*60)));
                $hours = ($buildingList[$key]->actual_buildtime / (60*60)) % 24;
                $minutes = ($buildingList[$key]->actual_buildtime / 60) % 60;
                $seconds = ($buildingList[$key]->actual_buildtime / 1) % 60;

                if($days > 0)
                {
                    $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
                } else {
                    $days = '';
                }

                $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
                $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
                $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

                $buildingList[$key]->readableBuildtime = $days . $hours . $minutes . $seconds;
            }

        }

        return $buildingList;
    }

    public static function fightCalculation($fleet)
    {
        Planet::getPlanetaryResourcesByPlanetId($fleet->target, $fleet->readableTarget->user_id);
        $allResearchForFight = Research::getAllResearchesWithEffect();
        $attacker["ship"] = json_decode($fleet->ship_types);
        $attacker["home"] = Planet::getOneById($fleet->planet_id);
        $attacker["research"] = Research::getUsersKnowledge($attacker["home"]->user_id);

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

        if($survivedAttRatio > 100)
        {
            $survivedAttRatio = 100;
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
        } else {
            $temp = new \stdClass();
            $temp->cargo = null;
        }

        $return = new \stdClass();
        $return->fleet = $fleet;
        $return->temp = $temp;
        $return->attacker = $attacker;
        $return->defender = $defender;

        return $return;
    }
}
