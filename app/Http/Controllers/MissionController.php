<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Fleet as Fleet;
use App\Models\Ship as Ship;
use App\Models\Research as Research;

class MissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::id();
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet[0]->start_planet]);
        return redirect('mission/' . $start_planet[0]->start_planet);
    }

    public function show(Request $request, $planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        Controller::checkResearchProcesses($allUserPlanets);
        Controller::checkShipProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $shipsAtPlanet = Fleet::getShipsAtPlanet($planet_id);
        $target = session('target');

        if($target)
        {
            $request->session()->forget('target');
        }

        if($shipsAtPlanet) {
            $shipsAtPlanet->ship_types = json_decode($shipsAtPlanet->ship_types);
        } else {
            $shipsAtPlanet = false;
        }

        if(count($planetaryResources)>0)
        {
            return view('mission.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'shipsAtPlanet' => $shipsAtPlanet,
                'target' => $target,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function start($planet_id)
    {
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $data = request()->validate([
            'galaxy' => 'required|integer',
            'system' => 'required|integer',
            'planet' => 'required|integer',
            'speed' => 'required|integer',
            'fleet' => ''
        ]);

        $source = Planet::getOneById($planet_id);
        $target = Planet::getPlanetByCoordinates($data["galaxy"], $data["system"], $data["planet"]);
        $knowledge = Research::getAllAvailableResearches($user_id, $planet_id);
        $maxPlanets = 10;
        $planetCount = count($allUserPlanets);
        $cargoIncreaser = [];

        foreach($knowledge as $research)
        {
            if($research->increase_max_planets != 0 && $research->knowledge != null)
            {
                $maxPlanets += $research->increase_max_planets * $research->knowledge->level;
            }

            if($research->increase_cargo != 0 && $research->knowledge != null)
            {
                $temp = new \stdClass();
                $temp->level = $research->knowledge->level;
                $temp->factor = $research->increase_cargo;

                $cargoIncreaser[] = $temp;
            }
        }

        // get ships data (cargo, consumption, atts, deffs, stealth, invasion, colo)
        // missions:
        // 1. stationierung (muss eigene)
        // 2. transport (kann eigene)
        // 3. spionage (muss anderer)
        // 4. delta scan (muss anderer)
        // 5. kolonisierung (muss anderer)
        // 6. angriff (muss anderer)
        // 7. invasion (muss anderer)
        if($target->user_id == $user_id)
        {
            $allowed_missions = [1,2];
        }

        if($target->user_id != $user_id)
        {
            $allowed_missions = [2,3,4,6,7];
        }

        if($target->user_id == null && $planetCount < $maxPlanets)
        {
            $allowed_missions = [4,5];
        }

        if($target->user_id == null && $planetCount >= $maxPlanets)
        {
            $allowed_missions = [4];
        }

        $targetProfile = Profile::where('user_id', $target->user_id)->first(['start_planet']);

        // check if target is source
        if($target->id == $planet_id)
        {
            return redirect('/mission/' . $planet_id);
        } else {
            // check if ships exists
            if(array_key_exists('fleet', $data))
            {
                // check if at least 1 ship was selected
                foreach($data["fleet"] as $key => $value)
                {
                    if($value == null || $value == 0)
                    {
                        unset($data["fleet"][$key]);
                    }
                }
                // no ships?
                if(count($data["fleet"]) == 0)
                {
                    return redirect('/mission/' . $planet_id);
                } else {
                    // ships selected
                    // get distance
                    if($source->galaxy < $target->galaxy) {
                        $galaxyDiff = $target->galaxy - $source->galaxy;
                    } else {
                        $galaxyDiff = $source->galaxy - $target->galaxy;
                    }
                    if($source->system < $target->system)
                    {
                        $systemDiff = $target->system - $source->system;
                    } else {
                        $systemDiff = $source->system - $target->system;
                    }
                    if($source->planet < $target->planet)
                    {
                        $planetDiff = $target->planet - $source->planet;
                    } else {
                        $planetDiff = $source->planet - $target->planet;
                    }

                    $distance = 1000000;

                    if($planetDiff > 0)
                    {
                        $distance += ($planetDiff * 5000);
                    }

                    if($systemDiff > 0)
                    {
                        $distance += 1700000;
                        $distance += ($systemDiff * 95000);
                    }

                    if($galaxyDiff > 0)
                    {
                        $distance += 20000000;
                        $distance += $galaxyDiff * 20000000;
                    }

                    $selectedShips = [];
                    foreach($data["fleet"] as $key => $ship)
                    {
                        $temp = Ship::getOneById($key);
                        $temp->amount = $ship;
                        $selectedShips[] = $temp;
                    }

                    //dd($knowledge);
                    $speedArray = [];
                    $cargo = 0;
                    $fuel = 0;
                    $attack = 0;
                    foreach($selectedShips as $key => $ship)
                    {
                        $singlefuel = $distance / $ship->speed * ($ship->consumption * ($data["speed"] / 100));
                        if($singlefuel > $ship->cargo)
                        {
                            // abort mission and report ship type
                            return redirect('/mission/' . $planet_id)->with('status', 'Ladekapazität nicht ausreichend für: ' . $ship->ship_name);
                        } else {
                            // put to complete fuel cost and multiply by amount
                            $fuel += ceil($singlefuel * $ship->amount) + $ship->amount;
                        }
                        $speedArray[] = $ship->speed;

                        if(count($cargoIncreaser) > 0)
                        {
                            foreach($cargoIncreaser as $increaser)
                            {
                                for($i = 0; $i < $increaser->level; $i++)
                                {
                                    $ship->cargo += $ship->cargo * ($increaser->factor / 100);
                                }
                            }
                        }

                        $attack += $ship->attack;

                        $cargo += ($ship->cargo * $ship->amount);
                        // check which missions are still allowed
                        if($ship->spy == 0) {
                            $pos = array_search(3, $allowed_missions);
                            if($pos != false) {
                                unset($allowed_missions[$pos]);
                            }
                        }

                        if($ship->delta_scan == 0) {
                            $pos = array_search(4, $allowed_missions);
                            if($pos != false) {
                                unset($allowed_missions[$pos]);
                            }
                        }

                        if($ship->colonization == 0) {
                            $pos = array_search(5, $allowed_missions);
                            if($pos != false) {
                                unset($allowed_missions[$pos]);
                            }
                        }
                        //dd($allowed_missions);

                        if($ship->invasion == 0) {
                            $pos = array_search(7, $allowed_missions);
                            if($pos != false) {
                                unset($allowed_missions[$pos]);
                            }
                        }

                    }
                    // it is not allowed to conquer main planets
                    if($targetProfile)
                    {
                        if($targetProfile->start_planet == $target->id)
                        {
                            $pos = array_search(7, $allowed_missions);
                            if($pos != false) {
                                unset($allowed_missions[$pos]);
                            }
                        }
                    }

                    // if attack value is 0 you cant attack
                    if($attack == 0) {
                        $pos = array_search(6, $allowed_missions);
                        if($pos != false) {
                            unset($allowed_missions[$pos]);
                        }
                    }

                    if(count($allowed_missions) == 0) {
                        return redirect('/mission/' . $planet_id)->with('status', 'Für dieses Ziel ist keine Mission mit der gewählten Flotte verfügbar.');
                    }

                    // need to much h2
                    if($cargo < 0)
                    {
                        return redirect('/mission/' . $planet_id)->with('status', 'Nicht genügend Ladekapazität');
                    }

                    // not enough h2?
                    if($planetaryResources[0][0]->h2 < $fuel)
                    {
                        return redirect('/mission/' . $planet_id)->with('status', 'Nicht genügend Treibstoff');
                    }

                    $lowestSpeed = min($speedArray);
                    $lowestSpeed *= ($data["speed"]/100);
                    $duration = $distance / (($lowestSpeed * ($data["speed"]/100) ) / 60 / 60);

                    // readable flighttime for FE
                    $timestamp =  floor($duration);
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

                    $flugzeit = $days . $hours . $minutes . $seconds;

                    if(count($planetaryResources)>0)
                    {
                        return view('mission.start', [
                            'defaultPlanet' => session('default_planet'),
                            'planetaryResources' => $planetaryResources[0][0],
                            'planetaryStorage' => $planetaryResources[1],
                            'allUserPlanets' => $allUserPlanets,
                            'activePlanet' => $planet_id,
                            'allowedMissions' => $allowed_missions,
                            'target' => $target,
                            'distance' => $distance,
                            'maxSpeed' => $lowestSpeed,
                            'duration' => $flugzeit,
                            'durationInSec' => $timestamp,
                            'arrival' => now()->timestamp+$duration,
                            'return' => now()->timestamp+($duration*2),
                            'cargo' => $cargo,
                            'fuel' => $fuel,
                            'selectedShips' => $selectedShips,
                        ]);
                    } else {
                        return view('error.index');
                    }
                }
            } else {
                return redirect('/mission/' . $planet_id)->with('status', 'Keine Schiffe ausgewählt');
            }
        }
    }

    public function liftoff($planet_id)
    {
        $user_id = Auth::id();
        Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $data = request()->validate([
            'mission' => '',
            'duration' => '',
            'fuel' => '',
            'target' => '',
            'selectedShips' => '',
            'cargo' => ''
        ]);

        if(!array_key_exists('mission', $data))
        {
            return redirect('/mission/' . $planet_id);
        } else {
            $target = json_decode($data["target"]);
            $selectedShips = json_decode($data["selectedShips"]);
            if(is_array($data["mission"]))
            {
                $mission = array_key_first($data["mission"]);
            } else {
                $mission = $data["mission"];
            }
            $resourceJson = null;

            switch($mission)
            {
                case 1:
                    // stationierung opt. resources
                    $resourceJson = [
                        "fe" => $data["mission"][$mission]["fe"] ?? 0,
                        "lut" => $data["mission"][$mission]["lut"] ?? 0,
                        "cry" => $data["mission"][$mission]["cry"] ?? 0,
                        "h2o" => $data["mission"][$mission]["h2o"] ?? 0,
                        "h2" => $data["mission"][$mission]["h2"] ?? 0,
                    ];
                    break;
                case 2:
                    // Transport opt. resources
                    $resourceJson = [
                        "fe" => $data["mission"][$mission]["fe"] ?? 0,
                        "lut" => $data["mission"][$mission]["lut"] ?? 0,
                        "cry" => $data["mission"][$mission]["cry"] ?? 0,
                        "h2o" => $data["mission"][$mission]["h2o"] ?? 0,
                        "h2" => $data["mission"][$mission]["h2"] ?? 0,
                    ];
                    break;
                case 5:
                    // Kolonisierung opt. resources
                    $resourceJson = [
                        "fe" => $data["mission"][$mission]["fe"] ?? 0,
                        "lut" => $data["mission"][$mission]["lut"] ?? 0,
                        "cry" => $data["mission"][$mission]["cry"] ?? 0,
                        "h2o" => $data["mission"][$mission]["h2o"] ?? 0,
                        "h2" => $data["mission"][$mission]["h2"] ?? 0,
                    ];
                    break;
            }

            $originalFleet = Fleet::getShipsAtPlanet($planet_id);
            $newFleet = [];
            $ship_types = json_decode($originalFleet->ship_types);
            foreach($ship_types as $key => $shipAtPlanet)
            {
                //dd($shipAtPlanet->ship_id);
                foreach($selectedShips as $keyB => $selectedShip)
                {
                    if($shipAtPlanet->ship_id == $selectedShip->id) {

                        // are there enough ships to be sent?
                        if($ship_types[$key]->amount - $selectedShip->amount < 0)
                        {
                            return redirect('/mission/' . $planet_id)->with('status', 'Es stehen nicht genügend Schiffe zur Verfügung.');
                        }

                        $ship_types[$key]->amount -= $selectedShip->amount;
                        $proof = false;

                        foreach($newFleet as $keyC => $existingShip)
                        {
                            if($existingShip->ship_id == $selectedShip->id)
                            {
                                $proof = true;
                                $existingKey = $keyC;
                            }
                        }
                        if(!$proof)
                        {
                            $temp = new \stdClass();
                            $temp->ship_id = $selectedShip->id;
                            $temp->ship_name = $selectedShip->ship_name;
                            $temp->amount = $selectedShip->amount;
                            $newFleet[] = $temp;
                        } else {
                            if($existingKey > 0)
                            {
                                $newFleet[$existingKey]->amount = $selectedShip->amount;
                            }
                        }
                    } else {
                        if(count($newFleet) == 0)
                        {
                            $temp = new \stdClass();
                            $temp->ship_id = $shipAtPlanet->ship_id;
                            $temp->ship_name = $shipAtPlanet->ship_name;
                            $temp->amount = 0;
                            $newFleet[] = $temp;
                        } else {
                            $proof = false;
                            foreach($newFleet as $keyC => $existingShip)
                            {
                                if($existingShip->ship_id == $shipAtPlanet->ship_id)
                                {
                                    $proof = true;
                                }
                            }
                            if(!$proof)
                            {
                                $temp = new \stdClass();
                                $temp->ship_id = $shipAtPlanet->ship_id;
                                $temp->ship_name = $shipAtPlanet->ship_name;
                                $temp->amount = 0;
                                $newFleet[] = $temp;
                            }
                        }
                    }
                }
            }
            $planet = Planet::getOneById($planet_id);

            // resources to be transported? check if enough is present
            if($resourceJson)
            {
                if($resourceJson["fe"] > floor($planet->fe) || $resourceJson["fe"] < 0)
                {
                    return redirect('/mission/' . $planet_id)->with('status', 'Nicht genügend Eisen');
                } else {
                    $planet->fe -= $resourceJson["fe"];
                }
                if($resourceJson["lut"] > floor($planet->lut) || $resourceJson["lut"] < 0)
                {
                    return redirect('/mission/' . $planet_id)->with('status', 'Nicht genügend Lutinum');
                } else {
                    $planet->lut -= $resourceJson["lut"];
                }
                if($resourceJson["cry"] > floor($planet->cry) || $resourceJson["cry"] < 0)
                {
                    return redirect('/mission/' . $planet_id)->with('status', 'Nicht genügend Kristalle');
                } else {
                    $planet->cry -= $resourceJson["cry"];
                }
                if($resourceJson["h2o"] > floor($planet->h2o) || $resourceJson["h2o"] < 0)
                {
                    return redirect('/mission/' . $planet_id)->with('status', 'Nicht genügend Wasser');
                } else {
                    $planet->h2o -= $resourceJson["h2o"];
                }
                if($resourceJson["h2"] > floor(($planet->h2 - $data["fuel"])) || $resourceJson["h2"] < 0)
                {
                    return redirect('/mission/' . $planet_id)->with('status', 'Nicht genügend Wasserstoff');
                } else {
                    $planet->h2 -= $resourceJson["h2"];
                }

                $proof = 0;
                foreach($resourceJson as $resource)
                {
                    $proof += $resource;
                }

                if($proof > $data["cargo"])
                {
                    return redirect('/mission/' . $planet_id)->with('status', 'Die Flotte hat keine ausreichende Ladekapazität.');
                }

            }

            $planet->h2 -= $data["fuel"];
            $planet->save();

            $originalFleet->ship_types = json_encode($ship_types);
            $originalFleet->save();

            Fleet::create([
                'planet_id' => $planet_id,
                'target' => $target->id,
                'mission' => $mission,
                'departure' => date('Y-m-d H:i:s',now()->timestamp),
                'arrival' => date('Y-m-d H:i:s',now()->timestamp + $data["duration"]),
                'ship_types' => json_encode($newFleet),
                'cargo' => json_encode($resourceJson)
            ]);

            return redirect('/overview/' . $planet_id);
        }
    }

    public function withdata($planet_id, $targetGalaxy = false, $targetSystem = false, $targetPlanet = false)
    {
        if($targetGalaxy == false || $targetSystem == false || $targetPlanet == false)
        {
            return redirect('/mission/' . $planet_id);
        } else {
            return redirect('/mission/' . $planet_id)->with('target', [$targetGalaxy, $targetSystem, $targetPlanet]);
        }
    }
}
