<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Report;
use App\Models\Statistics;
use App\Models\Turret;
use App\Models\User;
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
use App\Models\Defense as Defense;
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

    public static function checkBuildingProcesses($planet_ids)
    {
        $ids = [];
        foreach($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        $processes = DB::table('building_process AS bp')
            ->whereIn('planet_id', $ids)
            ->get();
        $user_id = Auth::id();
        if($processes) {
            foreach($processes as $process)
            {
                if($process)
                {
                    if(strtotime($process->finished_at) < now()->timestamp)
                    {
                        $infrastructure = Controller::getLevelForBuildingOnPlanet($process->planet_id, $process->building_id);

                        if(!$infrastructure)
                        {
                            // first build of this type
                            $levelUp = DB::table('infrastructures')
                                ->insert([
                                    'planet_id' => $process->planet_id,
                                    'building_id' => $process->building_id,
                                    'level' => 1
                                ]);
                        } else {
                            // at least lvl 1
                            $levelUp = DB::table('infrastructures')
                                ->where('planet_id', $process->planet_id)
                                ->where('building_id', $process->building_id)
                                ->update(['level' => ($infrastructure->level + 1)]);
                        }

                        if($levelUp)
                        {
                            $cleanBuildProcesses = DB::table('building_process')
                                ->where('planet_id', $process->planet_id)
                                ->delete();
                            if($cleanBuildProcesses)
                            {
                                // pick last needed Info
                                $buildingData = Building::find($process->building_id);
                                $planetData = Planet::find($process->planet_id);

                                if($buildingData["store_fe"] > 0 || $buildingData["store_lut"] > 0 || $buildingData["store_cry"] > 0 || $buildingData["store_h2o"] > 0 || $buildingData["store_h2"] > 0 ) {
                                    Building::updateStorageCapacity($process->planet_id);
                                }
                                self::calcResourceRatesForPlanet($process->planet_id);

                                $profile = Profile::where('user_id', Auth::id())->first();
                                $sendMessage = true;

                                if($profile->notifications == null) {
                                    $sendMessage = false;
                                } else {
                                    $notification = json_decode($profile->notifications);
                                    if(!property_exists($notification, 'construction')) {
                                        $sendMessage = false;
                                    }
                                }

                                if($sendMessage) {
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

    public static function checkResearchProcesses($planet_ids)
    {
        $ids = [];
        foreach($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        $processes = DB::table('research_process')
            ->whereIn('planet_id', $ids)
            ->get();
        $user_id = Auth::id();
        foreach($processes as $process)
        {
            $process = DB::table('research_process')
                         ->where('planet_id', $process->planet_id)
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
                                                 ->where('planet_id', $process->planet_id)
                                                 ->delete();

                        if($cleanResearchProcesses)
                        {
                            $profile = Profile::where('user_id', Auth::id())->first();
                            $sendMessage = true;

                            if($profile->notifications == null) {
                                $sendMessage = false;
                            } else {
                                $notification = json_decode($profile->notifications);
                                if(!property_exists($notification, 'research')) {
                                    $sendMessage = false;
                                }
                            }
                            if($sendMessage) {
                                // pick last needed Info
                                $researchData = Research::find($process->research_id);
                                $planetData = Planet::find($process->planet_id);

                                // emit system message to user
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $user_id,
                                    'subject' => 'Forschung Abgeschlossen',
                                    'message' => 'Forschung von ' . $researchData->research_name . ' (Stufe ' . ($knowledge ? $knowledge->level + 1 : 1) . ') auf ' . $planetData->galaxy . ':' . $planetData->system . ':' . $planetData->planet . ' wurde erfolgreich abgeschlossen.'
                                ];
                                Messages::create($message);
                            }
                        }
                    }
                }
            }
        }
    }

    public static function checkShipProcesses($planet_ids)
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
                        $temp->seconds = $timestamp;
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
                        $temp->seconds = $timestamp;
                        $temp->planet = $process->planet_id;
                        $buildTimes[] = $temp;
                    }
                }
            }
        }
        return $buildTimes;
    }

    public static function checkTurretProcesses($planet_ids)
    {
        $idList = [];
        foreach($planet_ids as $planet)
        {
            $idList[] = $planet->id;
        }

        $processes = DB::table('turrets_process AS tp')
                       ->whereIn('tp.planet_id', $idList)
                       ->get();

        $buildTimes = [];
        foreach($processes as $key => $process)
        {
            if($process)
            {
                if(strtotime($process->finished_at) < now()->timestamp)
                {
                    self::addTurretsToDefense($process->planet_id, $process->turret_id, $process->amount_left);
                    Defense::setEmptyProcessForPlanet($process->planet_id);
                } else {
                    if($process->planet_id)
                        // not finished, how long will it take to finish the NEXT ship?
                        //how many time is gone since last reload?
                        $diff = now()->timestamp - strtotime($process->started_at);
                    $done = floor($diff / $process->buildtime_single);
                    if($done > 0)
                    {
                        self::addTurretsToDefense($process->planet_id, $process->turret_id, $done);
                        $process->amount_left -= $done;
                        $process->started_at = strtotime($process->started_at) + ($done * $process->buildtime_single);
                        $process->buildtime_total -= ($done * $process->buildtime_single);
                        DB::table('turrets_process AS tp')
                          ->where('tp.planet_id', $process->planet_id)->update([
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
                        $temp->seconds = $timestamp;
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
                        $temp->seconds = $timestamp;
                        $temp->planet = $process->planet_id;
                        $buildTimes[] = $temp;
                    }
                }
            }
        }
        return $buildTimes;
    }

    public static function checkFleetProcesses($planet_ids)
    {
        $allfleets = Fleet::getFleetsOnMission($planet_ids);
        if($allfleets)
        {
            foreach($allfleets as $fleet)
            {
                $noReturn = false;

                if(strtotime($fleet->arrival) <= now()->timestamp && $fleet->arrived == false)
                {
                    // get defenders ship process
                    $targetPlanet = Planet::getOneById($fleet->target);
                    if($targetPlanet && $targetPlanet->user_id != null)
                    {
                       $planet_ids = Planet::getAllUserPlanets($targetPlanet->user_id);
                        self::checkShipProcesses($planet_ids);
                        self::checkTurretProcesses($planet_ids);
                    }
                    // ships are at target
                    switch ($fleet->mission)
                    {
                        case 1:
                            $target = Planet::getOneById($fleet->target);

                            $targetShips = Fleet::getShipsAtPlanet($fleet->target);
                            if($targetShips)
                            {
                                $targetFleet = json_decode($targetShips->ship_types);
                            } else {
                                $targetFleet = json_decode('[{"ship_id":1,"ship_name":"Spionagesonde","amount":0},{"ship_id":2,"ship_name":"Warpsonde","amount":0},{"ship_id":3,"ship_name":"Delta Dancer","amount":0},{"ship_id":4,"ship_name":"Crusader","amount":0},{"ship_id":5,"ship_name":"Sternenj\u00e4ger","amount":0},{"ship_id":6,"ship_name":"Tarnbomber","amount":0},{"ship_id":7,"ship_name":"Kolonisationsschiff","amount":0},{"ship_id":8,"ship_name":"Kleines Handelsschiff","amount":0},{"ship_id":9,"ship_name":"Gro\u00dfes Handelsschiff","amount":0},{"ship_id":10,"ship_name":"Akira","amount":0},{"ship_id":11,"ship_name":"Cobra","amount":0},{"ship_id":12,"ship_name":"Pegasus","amount":0},{"ship_id":13,"ship_name":"Phoenix","amount":0},{"ship_id":14,"ship_name":"Aurora","amount":0},{"ship_id":15,"ship_name":"Lavi","amount":0},{"ship_id":16,"ship_name":"Moskito","amount":0},{"ship_id":17,"ship_name":"Vega","amount":0},{"ship_id":18,"ship_name":"Black Dragon","amount":0},{"ship_id":19,"ship_name":"Invasionseinheit","amount":0}]');
                            }
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

                            if($targetShips)
                            {
                                $targetShips->ship_types = json_encode($targetFleet);
                                $targetShips->save();
                            } else {
                                Fleet::create([
                                    'planet_id' => $fleet->target,
                                    'ship_types' => json_encode($targetFleet)
                                ]);
                            }

                            // process done write message
                            // emit system message to user
                            $message = [
                                'user_id' => 0,
                                'receiver_id' => $target->user_id,
                                'subject' => 'Stationierung',
                                'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' wurde auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' stationiert.',
                            ];
                            Messages::create($message);
                            DB::table('fleets')->where('fleets.id', $fleet->id)->delete();

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
                                    'receiver_id' => $target->user_id,
                                    'subject' => 'Transportbericht',
                                    'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' lieferte an ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' folgende Rohstoffe:<br/>' . $resourcesList,
                                ];
                                Messages::create($message);

                                unset($fleet->sourceGalaxy);
                                unset($fleet->sourceSystem);
                                unset($fleet->sourcePlanet);
                                unset($fleet->targetGalaxy);
                                unset($fleet->targetSystem);
                                unset($fleet->targetPlanet);
                                $fleet->cargo = null;
                                DB::table('fleets AS f')
                                    ->where('f.id', '=', $fleet->id)
                                    ->update([
                                    'ship_types' => $fleet->ship_types,
                                    'cargo' => $fleet->cargo,
                                    'arrived' => $fleet->arrived,
                                ]);
                            }
                            break;
                        case 3:
                            $research = Research::all();
                            $sourceUser = Planet::getPlanetByCoordinates($fleet->sourceGalaxy, $fleet->sourceSystem, $fleet->sourcePlanet);
                            $targetUser = Planet::getPlanetByCoordinates($fleet->targetGalaxy, $fleet->targetSystem, $fleet->targetPlanet);
                            $attackerResearch = Research::getUsersKnowledge($sourceUser->user_id);
                            $attackerSpyIncrements = [];
                            $defenderResearch = Research::getUsersKnowledge($targetUser->user_id);
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

                                $defenderTurrets = Defense::getTurretsAtPlanet($fleet->target);
                                if($defenderTurrets == null)
                                {
                                    $defenderTurrets = null;
                                } else {
                                    $defenderTurrets = $defenderTurrets->turret_types;
                                }

                                $defenderResourcesRaw = Planet::getResourcesForPlanet($fleet->target);
                                $defenderResources = $defenderResourcesRaw[0];

                                $resourceJson = [
                                    "fe" => ceil($defenderResources->fe),
                                    "lut" => ceil($defenderResources->lut),
                                    "cry" => ceil($defenderResources->cry),
                                    "h2o" => ceil($defenderResources->h2o),
                                    "h2" => ceil($defenderResources->h2),
                                ];

                                // create report
                                $report = Report::create([
                                    'link' => Uuid::uuid4(),
                                    'attacker_id' => $sourceUser->user_id,
                                    'defender_id' => $targetUser->user_id,
                                    'attacker_fleet' => null,
                                    'defender_fleet' => $defenderShips,
                                    'defender_defense' => $defenderTurrets,
                                    'resources' => json_encode($resourceJson),
                                    'attacker_planet_id' => $fleet->planet_id,
                                    'defender_planet_id' => $fleet->target,
                                    'report_type' => 2,
                                ]);

                                $link = Report::where('id', $report->id)->first();
                                // emit system message to users
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $sourceUser->user_id,
                                    'subject' => 'Spionagebericht',
                                    'message' => 'Spionagemission von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' erfolgte auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $targetUser->user_id,
                                    'subject' => 'Spionagebericht',
                                    'message' => 'Spionagemission von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' erfolgte auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                            } else {
                                // create report
                                $report = Report::create([
                                    'link' => Uuid::uuid4(),
                                    'attacker_id' => $sourceUser->user_id,
                                    'defender_id' => $targetUser->user_id,
                                    'attacker_fleet' => null,
                                    'defender_fleet' => null,
                                    'defender_defense' => null,
                                    'resources' => null,
                                    'attacker_planet_id' => $sourceUser->id,
                                    'defender_planet_id' => $targetUser->id,
                                    'report_type' => 0,
                                ]);

                                $link = Report::where('id', $report->id)->first();
                                // emit system message to users
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $sourceUser->user_id,
                                    'subject' => 'Spionagebericht',
                                    'message' => 'Spionagemission von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' schlug fehl. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $targetUser->user_id,
                                    'subject' => 'Spionagebericht',
                                    'message' => 'Spionagemission von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' erfolgreich verhindert. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                            }


                            // drones die, so delete fleet
                            DB::table('fleets')->where('fleets.id', $fleet->id)->delete();
                            break;
                        case 4:
                            // is planet occupied by another user?
                            $sourceUser = Planet::getPlanetByCoordinates($fleet->sourceGalaxy, $fleet->sourceSystem, $fleet->sourcePlanet);
                            $targetUser = Planet::getPlanetByCoordinates($fleet->targetGalaxy, $fleet->targetSystem, $fleet->targetPlanet);
                            if($targetUser->user_id != null)
                            {
                                $research = Research::all();
                                $attackerResearch = Research::getUsersKnowledge($sourceUser->user_id);
                                $attackerSpyIncrements = [];
                                $defenderResearch = Research::getUsersKnowledge($targetUser->user_id);
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

                                    $defenderTurrets = Defense::getTurretsAtPlanet($fleet->target);
                                    if($defenderTurrets == null)
                                    {
                                        $defenderTurrets = null;
                                    } else {
                                        $defenderTurrets = $defenderTurrets->turret_types;
                                    }

                                    $defenderResourcesRaw = Planet::getResourcesForPlanet($fleet->target);
                                    $defenderResources = $defenderResourcesRaw;

                                    $resourceJson = [
                                        "fe" => ceil($defenderResources['fe']),
                                        "lut" => ceil($defenderResources['lut']),
                                        "cry" => ceil($defenderResources['cry']),
                                        "h2o" => ceil($defenderResources['h2o']),
                                        "h2" => ceil($defenderResources['h2']),
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
                                        'attacker_id' => $sourceUser->user_id,
                                        'defender_id' => $targetUser->user_id,
                                        'attacker_fleet' => null,
                                        'defender_fleet' => $defenderShips,
                                        'defender_defense' => $defenderTurrets,
                                        'resources' => json_encode($resourceJson),
                                        'attacker_planet_id' => $fleet->planet_id,
                                        'defender_planet_id' => $fleet->target,
                                        'report_type' => 1,
                                        'planet_info' => json_encode($infoJson),
                                        'planet_infrastructure' => json_encode($defenderFullBuildingList),
                                        'defender_knowledge' => json_encode($defenderFullResearchList),
                                    ]);

                                    $link = Report::where('id', $report->id)->first();
                                    // emit system message to users
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $sourceUser->user_id,
                                        'subject' => 'Delta Scan',
                                        'message' => 'Delta Scan von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' erfolgte auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $targetUser->user_id,
                                        'subject' => 'Delta Scan',
                                        'message' => 'Delta Scan von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' erfolgte auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                } else {
                                    // create report
                                    $report = Report::create([
                                        'link' => Uuid::uuid4(),
                                        'attacker_id' => $sourceUser->user_id,
                                        'defender_id' => $targetUser->user_id,
                                        'attacker_fleet' => null,
                                        'defender_fleet' => null,
                                        'defender_defense' => null,
                                        'resources' => null,
                                        'attacker_planet_id' => $fleet->planet_id,
                                        'defender_planet_id' => $fleet->target,
                                        'report_type' => 0,
                                    ]);

                                    $link = Report::where('id', $report->id)->first();
                                    // emit system message to users
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $sourceUser->user_id,
                                        'subject' => 'Delta Scan',
                                        'message' => 'Delta Scan von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' schlug fehl. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                    ];
                                    Messages::create($message);
                                    $message = [
                                        'user_id' => 0,
                                        'receiver_id' => $targetUser->user_id,
                                        'subject' => 'Delta Scan',
                                        'message' => 'Delta Scan von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' erfolgreich verhindert. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
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
                                    'attacker_id' => $sourceUser->user_id,
                                    'defender_id' => $targetUser->user_id,
                                    'attacker_fleet' => null,
                                    'defender_fleet' => null,
                                    'defender_defense' => null,
                                    'resources' => null,
                                    'attacker_planet_id' => $fleet->planet_id,
                                    'defender_planet_id' => $fleet->target,
                                    'report_type' => 1,
                                    'planet_info' => json_encode($infoJson),
                                ]);

                                $link = Report::where('id', $report->id)->first();
                                // emit system message to users
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $sourceUser->user_id,
                                    'subject' => 'Delta Scan',
                                    'message' => 'Der Delta Scan von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' auf ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' war erfolgreich. (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                            }
                            unset($fleet->sourceGalaxy);
                            unset($fleet->sourceSystem);
                            unset($fleet->sourcePlanet);
                            unset($fleet->targetGalaxy);
                            unset($fleet->targetSystem);
                            unset($fleet->targetPlanet);
                            $fleet->arrived = true;
                            DB::table('fleets AS f')
                                ->where('f.id', '=', $fleet->id)
                                ->update([
                                    'ship_types' => $fleet->ship_types,
                                    'cargo' => $fleet->cargo,
                                    'arrived' => $fleet->arrived,
                                ]);

                            break;
                        case 5:
                            $shipTypes = json_decode($fleet->ship_types);
                            $targetUser = Planet::getPlanetByCoordinates($fleet->targetGalaxy, $fleet->targetSystem, $fleet->targetPlanet);
                            foreach($shipTypes as $key => $shipType) {
                                if($shipType->ship_name == "Kolonisationsschiff")
                                {
                                    $shipTypes[$key]->amount -= 1;
                                }
                            }
                            $fleet->ship_types = json_encode($shipTypes);

                            if($targetUser && $targetUser->user_id == null)
                            {
                                // emit system message to user
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => Auth::id(),
                                    'subject' => 'Kolonisierung',
                                    'message' => 'Der Planet ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' wurde erfolgreich kolonisiert.',
                                ];
                                Messages::create($message);

                                $fleet->planet_id = $fleet->target;
                                $fleet->target = null;
                                $fleet->mission = null;
                                $fleet->arrival = null;
                                $fleet->departure = null;

                                Planet::where('id', $fleet->planet_id)->update([
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

                                DB::table('fleets')->where('fleets.id', $fleet->id)->update([
                                    'planet_id' => $fleet->planet_id,
                                    'target' => $fleet->target,
                                    'mission' => $fleet->mission,
                                    'arrival' => $fleet->arrival,
                                    'departure' => $fleet->departure,
                                    'ship_types' => $fleet->ship_types,
                                    'cargo' => null,
                                    'arrived' => 0
                                ]);
                                return true;
                            } else {
                                dd('error while colonization');
                            }
                            break;
                        case 6:
                            $result = self::fightCalculation($fleet);
                            $sourceUser = Planet::getPlanetByCoordinates($fleet->sourceGalaxy, $fleet->sourceSystem, $fleet->sourcePlanet);
                            $targetUser = Planet::getPlanetByCoordinates($fleet->targetGalaxy, $fleet->targetSystem, $fleet->targetPlanet);

                            $fleet = $result->fleet;
                            $temp = $result->temp;
                            $attacker = $result->attacker;
                            $defender = $result->defender;

                            if(!isset($defender["turrets"]))
                            {
                                $defender["turrets"] = [];
                            }

                            // create report
                            $report = Report::create([
                                'link' => Uuid::uuid4(),
                                'attacker_id' => $sourceUser->user_id,
                                'defender_id' => $targetUser->user_id,
                                'attacker_fleet' => json_encode($attacker["ship"]),
                                'defender_fleet' => json_encode($defender["ship"]),
                                'defender_defense' => json_encode($defender["turrets"]),
                                'resources' => $temp->cargo,
                                'attacker_planet_id' => $fleet->planet_id,
                                'defender_planet_id' => $fleet->target,
                                'report_type' => 3,
                            ]);

                            $link = Report::where('id', $report->id)->first();
                            // emit system message to users
                            $message = [
                                'user_id' => 0,
                                'receiver_id' => $sourceUser->user_id,
                                'subject' => 'Kampfbericht',
                                'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' griff ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' an (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                            ];
                            Messages::create($message);
                            $message = [
                                'user_id' => 0,
                                'receiver_id' => $targetUser->user_id,
                                'subject' => 'Kampfbericht',
                                'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' griff ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' an (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                            ];
                            Messages::create($message);

                            // set attacker fleet
                            if($attacker["hasSurvived"])
                            {
                                $temp->arrived = true;
                                unset($temp->sourceGalaxy);
                                unset($temp->sourceSystem);
                                unset($temp->sourcePlanet);
                                unset($temp->targetGalaxy);
                                unset($temp->targetSystem);
                                unset($temp->targetPlanet);
                                DB::table('fleets')->where('fleets.id', $fleet->id)->update([
                                    'ship_types' => $temp->ship_types,
                                    'cargo' => $temp->cargo,
                                    'arrived' => $temp->arrived,
                                ]);
                            } else {
                                DB::table('fleets')->where('fleets.id', $fleet->id)->delete();
                            }

                            // set defender fleet
                            $defenderList = [];
                            $defenderTurretList = [];
                            if($defender["turrets"])
                            {
                                foreach($defender["turrets"] as $key => $defenderTurret)
                                {
                                    $temp            = new \stdClass();
                                    $temp->turret_id   = $defenderTurret->turret_id;
                                    $temp->turret_name = $defenderTurret->turret_name;
                                    $temp->amount    = $defenderTurret->newAmount;
                                    $defenderTurretList[]  = $temp;
                                }
                                $defender->turret_types = json_encode($defenderTurretList);
                                Defense::where('planet_id', $fleet->target)->update(['turret_types' => $defender->turret_types]);
                            }


                            if($defender["ship"])
                            {
                                foreach ( $defender["ship"] as $key => $defenderShip )
                                {
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
                                unset( $defender["turrets"] );
                                unset( $defender->turret_types );
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
                            $sourceUser = Planet::getPlanetByCoordinates($fleet->sourceGalaxy, $fleet->sourceSystem, $fleet->sourcePlanet);
                            $targetUser = Planet::getPlanetByCoordinates($fleet->targetGalaxy, $fleet->targetSystem, $fleet->targetPlanet);

                            if($attacker["survivedAttRatio"] > 92 && $defender["survivedDefRatio"] <= 0 && $defenderHome->start_planet != $fleet->readableTarget->id)
                            {

                                $report = Report::create([
                                    'link' => Uuid::uuid4(),
                                    'attacker_id' => $sourceUser->user_id,
                                    'defender_id' => $targetUser->user_id,
                                    'attacker_fleet' => json_encode($attacker["ship"]),
                                    'defender_fleet' => json_encode($defender["ship"]),
                                    'defender_defense' => json_encode($defender["turrets"]),
                                    'resources' => $temp->cargo,
                                    'attacker_planet_id' => $fleet->planet_id,
                                    'defender_planet_id' => $fleet->target,
                                    'report_type' => 4,
                                ]);

                                $link = Report::where('id', $report->id)->first();
                                // emit system message to users
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $sourceUser->user_id,
                                    'subject' => 'Invasionsbericht',
                                    'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' eroberte ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $targetUser->user_id,
                                    'subject' => 'Invasionsbericht',
                                    'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' eroberte ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);

                                // delete defender fleet if there was
                                if($defender["ship"])
                                {
                                    $defender->delete();
                                }

                                // delete defender defense
                                $turrets = Defense::getTurretsAtPlanet($fleet->target);
                                if($turrets)
                                {
                                    $turrets->delete();
                                }

                                // rewrite user id for planet
                                $targetPlanet->user_id = $sourceUser->user_id;
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

                                $noReturn = true;

                                $fleet->save();
                            } else {
                                // create report
                                $report = Report::create([
                                    'link' => Uuid::uuid4(),
                                    'attacker_id' => $sourceUser->user_id,
                                    'defender_id' => $targetUser->user_id,
                                    'attacker_fleet' => json_encode($attacker["ship"]),
                                    'defender_fleet' => json_encode($defender["ship"]),
                                    'defender_defense' => json_encode($defender["turrets"]),
                                    'resources' => $temp->cargo,
                                    'attacker_planet_id' => $fleet->planet_id,
                                    'defender_planet_id' => $fleet->target,
                                    'report_type' => 3,
                                ]);

                                $link = Report::where('id', $report->id)->first();
                                // emit system message to users
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $sourceUser->user_id,
                                    'subject' => 'Kampfbericht',
                                    'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' griff ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' an (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);
                                $message = [
                                    'user_id' => 0,
                                    'receiver_id' => $targetUser->user_id,
                                    'subject' => 'Kampfbericht',
                                    'message' => 'Flotte von ' . $fleet->sourceGalaxy .':'. $fleet->sourceSystem . ':' . $fleet->sourcePlanet . ' griff ' . $fleet->targetGalaxy .':'. $fleet->targetSystem . ':' . $fleet->targetPlanet . ' an (<a href="/report/' . $link->link . '">Zum Bericht</a>)',
                                ];
                                Messages::create($message);

                                // set attacker fleet
                                if($attacker["hasSurvived"])
                                {
                                    $temp->arrived = true;
                                    $temp->mission = 6;
                                    $temp->save();
                                } else {
                                    DB::table('fleets')->where('fleets.id', $fleet->id)->delete();
                                }

                                // set defender fleet
                                $defenderList = [];
                                $defenderTurretList = [];
                                if($defender["turrets"])
                                {
                                    foreach($defender["turrets"] as $key => $defenderTurret)
                                    {
                                        $temp            = new \stdClass();
                                        $temp->turret_id   = $defenderTurret->turret_id;
                                        $temp->turret_name = $defenderTurret->turret_name;
                                        $temp->amount    = $defenderTurret->newAmount;
                                        $defenderTurretList[]  = $temp;
                                    }
                                    $defender->turret_types = json_encode($defenderTurretList);
                                    Defense::where('planet_id', $fleet->target)->update(['turret_types' => $defender->turret_types]);
                                    unset($defender["turrets"]);
                                    unset($defender->turret_types);
                                }

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
                        'receiver_id' => $homePlanet->user_id,
                        'subject' => 'Flottenaktivität',
                        'message' => 'Eine Flotte kehrt zurück',
                    ];
                    Messages::create($message);
                    DB::table('fleets')->where('fleets.id', $fleet->id)->delete();
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

    private static function addTurretsToDefense($planet_id, $turret_id, $amount)
    {
        // turret is done, add it to planetary defense
        $defense = Defense::getTurretsAtPlanet($planet_id);
        if($defense)
        {
            // add to existing defense
            $turret_types = json_decode($defense->turret_types);
            foreach($turret_types as $key => $turret_type)
            {
                if($turret_type->turret_id == $turret_id)
                {
                    $turret_types[$key]->amount += $amount;
                }
            }

            $defense->turret_types = json_encode($turret_types);
            $defense->save();
            return true;

        } else {
            // add new Defense to planet
            $proof = Defense::setDefenseForPlanet($planet_id, $turret_id, $amount);
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

            $isStorage = false;
            if($building->store_fe > 0)
            {
                $isStorage = true;
            }
            if($building->store_lut > 0)
            {
                $isStorage = true;
            }
            if($building->store_cry > 0)
            {
                $isStorage = true;
            }
            if($building->store_h2o > 0)
            {
                $isStorage = true;
            }
            if($building->store_h2 > 0)
            {
                $isStorage = true;
            }

            // calc resource cost EXCEPT storages
            if($building->infrastructure != null && !$isStorage)
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

    public static function fightCalculation($fleet, $sim = false)
    {
        $allResearchForFight = Research::getAllResearchesWithEffect();

        if(!$sim)
        {
            $targetUser = Planet::getOneById($fleet->target);
            Planet::getResourcesForPlanet($fleet->target);
            $attacker["ship"] = json_decode($fleet->ship_types);
            $attacker["home"] = Planet::getOneById($fleet->planet_id);
            $attacker["research"] = Research::getUsersKnowledge($attacker["home"]->user_id);

            $defender = Fleet::getShipsAtPlanet($fleet->target);
            $turrets = Defense::getTurretsAtPlanet($fleet->target);

            if($defender) {
                $defender["ship"] = json_decode($defender->ship_types);
            } else {
                $defender["ship"] = false;
            }
            $defender["home"] = Planet::getOneById($fleet->target);
            $defender["research"] = Research::getUsersKnowledge($defender["home"]->user_id);
        } else {
            $attacker["ship"] = [];
            foreach($fleet["sim"]["att"]["ship"] as $key => $value)
            {
                $temp = new \stdClass();
                $temp->ship_id = $key;
                $temp->amount = intval($value);
                array_push($attacker["ship"], $temp);
            }

            $attacker["research"] = [];
            foreach($fleet["sim"]["att"]["research"] as $key => $value)
            {
                $temp = new \stdClass();
                $temp->research_id = $key;
                $temp->level = intval($value);
                array_push($attacker["research"], $temp);
            }

            $defender["ship"] = [];
            foreach($fleet["sim"]["def"]["ship"] as $key => $value)
            {
                $temp = new \stdClass();
                $temp->ship_id = $key;
                $temp->amount = intval($value);
                array_push($defender["ship"], $temp);
            }

            $defender["research"] = [];
            foreach($fleet["sim"]["def"]["research"] as $key => $value)
            {
                $temp = new \stdClass();
                $temp->research_id = $key;
                $temp->level = intval($value);
                array_push($defender["research"], $temp);
            }
            $turrets = [];
            foreach($fleet["sim"]["def"]["def"] as $key => $value)
            {
                $temp = new \stdClass();
                $temp->turret_id = $key;
                $temp->amount = intval($value);
                array_push($turrets, $temp);
            }

        }
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
        foreach($allResearchForFight as $research)
        {
            foreach($attacker["research"] as $key => $attackerResearch)
            {
                if($attackerResearch->research_id != null)
                {
                    if($research->research_id == $attackerResearch->research_id)
                    {
                        $attacker["research"][$key] = $research;
                        $attacker["research"][$key]->level = $attackerResearch->level;
                    }
                }


            }
        }
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
                        $temp += $attacker["attack_value"] * ($research->increase_ship_attack / 100);
                    }
                    $attacker["final_attack_value"] += $temp;
                }

                if(property_exists($research, 'increase_ship_defense') && $research->increase_ship_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += $attacker["defense_value"] * ($research->increase_ship_defense / 100);
                    }
                    $attacker["final_defense_value"] += $temp;
                }

                if(property_exists($research, 'increase_shield_defense') && $research->increase_shield_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += $attacker["defense_value"] * ($research->increase_shield_defense / 100);
                    }
                    $attacker["final_shield_value"] += $temp;
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
                    $ship->destroyable = $defenderList[$key]->destroyable;
                    if($defenderList[$key]->destroyable) {
                        $defenderList[$key]->amount = $ship->amount;
                        $defender["attack_value"] += $defenderList[$key]->amount * $defenderList[$key]->attack;
                        $defender["defense_value"] += $defenderList[$key]->amount * $defenderList[$key]->defend;
                    }
                }
            }
        }
        foreach($allResearchForFight as $research)
        {
            foreach($defender["research"] as $key => $defenderResearch)
            {

                if($research->research_id == $defenderResearch->research_id)
                {

                    $temp = clone $research;
                    $temp->level = $defenderResearch->level;
                    $defender["research"][$key] = $temp;
                }
            }
        }

        // calc final values for attack
        if(count($defender["research"]) > 0)
        {
            foreach($defender["research"] as $research)
            {
                if(property_exists($research, 'increase_ship_attack') && $research->increase_ship_attack > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += $defender["attack_value"] * ($research->increase_ship_attack / 100);
                    }
                    $defender["final_attack_value"] += $temp;
                }

                if(property_exists($research, 'increase_ship_defense') && $research->increase_ship_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += $defender["defense_value"] * ($research->increase_ship_defense / 100);
                    }
                    $defender["final_defense_value"] += $temp;
                }

                if(property_exists($research, 'increase_shield_defense') && $research->increase_shield_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += $defender["defense_value"] * ($research->increase_shield_defense / 100);
                    }
                    $defender["final_shield_value"] += $temp;
                }
            }
        }

        $turretList = [];

        if($turrets)
        {
            $turretAtt = 0;
            $turretDef = 0;

            if(is_string($turrets))
            {
                $turrets = json_decode($turrets->turret_types);
            }

            foreach($turrets as $turret)
            {
                if(is_object($turret))
                {
                    if(property_exists($turret, 'turret_id'))
                    {
                        $tempTurret = Turret::getOneById($turret->turret_id)->first();
                        $tempListEntry = new \stdClass();
                        $tempListEntry->turret_id = $turret->turret_id;
                        $tempListEntry->turret_name = $tempTurret->turret_name;
                        $tempListEntry->amount = $turret->amount;
                        $turretList[] = $tempListEntry;
                        $turretAtt += $tempTurret->attack * $turret->amount;
                        $turretDef += $tempTurret->defend * $turret->amount;
                    }
                }


            }

            $defender["final_attack_value"] += $turretAtt;
            $defender["final_defense_value"] += $turretDef;

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

            if($attackerShip->newAmount < 0)
            {
                $attackerShip->newAmount = 0;
            }
        }

        if($defender["ship"]) {

            foreach($defender["ship"] as $key => $defenderShip)
            {
                if($defenderShip->amount > 0 && $defenderShip->destroyable) {
                    $defenderShip->newAmount = ceil($defenderShip->amount * ($survivedDefRatio/100));
                } else {
                    $defenderShip->newAmount = $defenderShip->amount;
                }
            }
        }

        if($turrets) {
            foreach($turretList as $key => $defenderTurret)
            {
                $defenderTurret->newAmount = ceil($defenderTurret->amount * ($survivedDefRatio/100));
            }

            $defender["turrets"] = $turretList;
        }
        if(!$sim)
        {
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

                $buildingsList = Building::getAllAvailableBuildings($fleet->target, $targetUser->user_id);

                $storage = new \stdClass();
                $storage->max_fe = 10000;
                $storage->max_lut = 10000;
                $storage->max_cry = 100;
                $storage->max_h2o = 10000;
                $storage->max_h2 = 1000;

                foreach($buildingsList as $building) {
                    if($building->store_fe > 0) {
                        if($building->infrastructure && $building->infrastructure->level > 0) {
                            $storage->max_fe += $building->store_fe * $building->infrastructure->level;
                        }
                    }
                    if($building->store_lut > 0) {
                        if($building->infrastructure && $building->infrastructure->level > 0) {
                            $storage->max_lut += $building->store_lut * $building->infrastructure->level;
                        }
                    }
                    if($building->store_cry > 0) {
                        if($building->infrastructure && $building->infrastructure->level > 0) {
                            $storage->max_cry += $building->store_cry * $building->infrastructure->level;
                        }
                    }
                    if($building->store_h2o > 0) {
                        if($building->infrastructure && $building->infrastructure->level > 0) {
                            $storage->max_h2o += $building->store_h2o * $building->infrastructure->level;
                        }
                    }
                    if($building->store_h2 > 0) {
                        if($building->infrastructure && $building->infrastructure->level > 0) {
                            $storage->max_h2 += $building->store_h2 * $building->infrastructure->level;
                        }
                    }
                }

                if($defender["home"]->fe > -1)
                {
                    $maxFe = floor($defender["home"]->fe - ($storage->max_fe * 0.04));
                    $maxFe = $maxFe < 0 ? 0 : $maxFe;
                    if($maxFe < $cargoFe) {
                        $cargoFe = $maxFe;
                    }
                }
                $defender["home"]->fe -= $cargoFe;

                if($defender["home"]->lut > -1)
                {
                    $maxLut = floor($defender["home"]->lut - ($storage->max_lut * 0.04));
                    $maxLut = $maxLut < 0 ? 0 : $maxLut;
                    if($maxLut < $cargoLut) {
                        $cargoLut = $maxLut;
                    }
                }
                $defender["home"]->lut -= $cargoLut;

                if($defender["home"]->cry > -1)
                {

                    $maxCry = floor($defender["home"]->cry - ($storage->max_cry * 0.04));
                    $maxCry = $maxCry < 0 ? 0 : $maxCry;
                    if($cargoCry > $maxCry) {
                        $cargoCry = $maxCry;
                    }
                }
                $defender["home"]->cry -= $cargoCry;

                if($defender["home"]->h2o > -1)
                {
                    $maxH2o = floor($defender["home"]->h2o - ($storage->max_h2o * 0.04));
                    $maxH2o = $maxH2o < 0 ? 0 : $maxH2o;
                    if($maxH2o < $cargoH2o) {
                        $cargoH2o = $maxH2o;
                    }
                }
                $defender["home"]->h2o -= $cargoH2o;

                if($defender["home"]->h2 > -1)
                {
                    $maxH2 = floor($defender["home"]->h2- ($storage->max_h2 * 0.04));
                    $maxH2 = $maxH2 < 0 ? 0 : $maxH2;
                    if($maxH2 < $cargoH2) {
                        $cargoH2 = $maxH2;
                    }
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
            // report to statistics
            Statistics::addValues($attacker, $defender, $fleet->cargo);
        } else {
            $return = new \stdClass();
            $return->attacker = $attacker;
            $return->defender = $defender;
        }
        return $return;
    }

    public static function checkForLostFleets($planet_ids)
    {
        $ids = [];
        foreach ($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        // run through every planet and get all fleets with target and mission null to combine them
        // first should be the main fleet
        $fleetEntries = Fleet::whereIn("planet_id", $ids)
            ->where([
            "mission" => null,
            "target" => null
            ])->get();

        // more fleets present as planets? -> fleet is not correctly added to planets main fleet
        if(count($fleetEntries) > count($ids)) {
            $fleetEntries->groupBy('planet_id')->flatMap(function($fleets) {
                foreach($fleets as $key => $fleet) {
                    $fleets[$key]->ship_types = json_decode($fleet->ship_types);
                    if($key > 0) {
                        foreach($fleet->ship_types as $ship) {
                            foreach($fleets[0]->ship_types as $originalShip) {
                                if($ship->ship_id == $originalShip->ship_id) {
                                    $originalShip->amount += $ship->amount;
                                }
                            }
                        }
                        $fleets[$key]->delete();
                        $fleets[0]->ship_type = json_encode($fleets[0]->ship_types);
                        $fleets[0]->save();
                    }
                }
            });
        }
    }

    public static function checkForLostTurrets($planet_ids)
    {
        $ids = [];
        foreach ($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        // run through every planet and get all turrets
        // first should be the main defense
        $turretEntries = Defense::whereIn("planet_id", $ids)->get();

        // more turrets present as planets? -> turret is not correctly added to planets defense
        if(count($turretEntries) > count($ids)) {
            $turretEntries->groupBy('planet_id')->flatMap(function($turrets) {
                foreach($turrets as $key => $turret) {
                    $turrets[$key]->turret_types = json_decode($turret->turret_types);
                    if($key > 0) {
                        foreach($turret->turret_types as $turret) {
                            foreach($turrets[0]->turret_types as $originalTurret) {
                                if($turret->turret_id == $originalTurret->turret_id) {
                                    $originalTurret->amount += $turret->amount;
                                }
                            }
                        }
                        $turrets[$key]->delete();
                        $turrets[0]->turret_type = json_encode($turrets[0]->turret_types);
                        $turrets[0]->save();
                    }
                }
            });
        }
    }

    public static function checkAllProcesses($planet_ids)
    {
//        $start = microtime(true);
//        $count = 0;
//        DB::listen(function ($query) use (&$count) {
//            $count++;
//        });
        //Planet::getResourcesForAllPlanets($planet_ids);
        
        self::checkBuildingProcesses($planet_ids);
        self::checkResearchProcesses($planet_ids);
        self::checkShipProcesses($planet_ids);
        self::checkTurretProcesses($planet_ids);
        self::checkFleetProcesses($planet_ids);
        self::checkForLostFleets($planet_ids);
        self::checkForLostTurrets($planet_ids);
        //$time_elapsed_secs = microtime(true) - $start;
        //dd($time_elapsed_secs);
        //dd($count);
    }
}
