<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Ship as Ship;
use App\Models\Building as Building;

class ShipyardController extends Controller
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
        return redirect('shipyard/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);
        Controller::checkResearchProcesses($allUserPlanets);
        $nextShipIn = Controller::checkShipProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $shipList = Ship::getAllAvailableShips($user_id, $planet_id);

        $currentShips = Planet::getPlanetaryShipProcess($planet_id);

        if(is_bool($nextShipIn) || $nextShipIn == null || $currentShips == null)
        {
            $currentShips = false;
        } else {
            $currentShips->nextShipIn = $nextShipIn;
        }

        self::populateBuildtimes($planet_id, $shipList);

        foreach($shipList as $key => $ship)
        {
            $maxWithFe = true;
            $maxWithLut = true;
            $maxWithCry = true;
            $maxWithH2o = true;
            $maxWithH2 = true;

            if($ship->fe > 0)
            {
                $maxWithFe = floor($planetaryResources[0][0]->fe / $ship->fe);
            }
            if($ship->lut > 0)
            {
                $maxWithLut = floor($planetaryResources[0][0]->lut / $ship->lut);
            }
            if($ship->cry > 0)
            {
                $maxWithCry = floor($planetaryResources[0][0]->cry / $ship->cry);
            }
            if($ship->h2o > 0)
            {
                $maxWithH2o = floor($planetaryResources[0][0]->h2o / $ship->h2o);
            }
            if($ship->h2 > 0)
            {
                $maxWithH2 = floor($planetaryResources[0][0]->h2 / $ship->h2);
            }

            $maxBuildable = min($maxWithFe, $maxWithLut, $maxWithCry, $maxWithH2o, $maxWithH2);
            $shipList[$key]->max_amount = $maxBuildable;
        }

        if(count($planetaryResources)>0)
        {
            return view('shipyard.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'shipList' => $shipList,
                'currentShips' => $currentShips,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function build($planet_id)
    {
        $data = request()->all();
        $shipsToBuild = $data["ship"];
        $user_id = Auth::id();
        $availableResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $availableShips = Ship::getAllAvailableShips($user_id, $planet_id);
        $shipyard = Building::getOneByNameWithData($planet_id, "Schiffswerft");

        self::populateBuildtimes($planet_id, $availableShips);

        foreach($shipsToBuild as $key => $shipAmount)
        {
            foreach($availableShips as $keyB => $available_ship)
            {
                if($key == $available_ship->ship_name)
                {
                    if($available_ship->buildable)
                    {
                        // ship can theoretically be built
                        // check resources to get lowest max amount
                        $maxWithFe = 99999999;
                        $maxWithLut = 99999999;
                        $maxWithCry = 99999999;
                        $maxWithH2o = 99999999;
                        $maxWithH2 = 99999999;

                        if($available_ship->fe > 0)
                        {
                            $maxWithFe = floor($availableResources[0][0]->fe / $available_ship->fe);
                        }
                        if($available_ship->lut > 0)
                        {
                            $maxWithLut = floor($availableResources[0][0]->lut / $available_ship->lut);
                        }
                        if($available_ship->cry > 0)
                        {
                            $maxWithCry = floor($availableResources[0][0]->cry / $available_ship->cry);
                        }
                        if($available_ship->h2o > 0)
                        {
                            $maxWithH2o = floor($availableResources[0][0]->h2o / $available_ship->h2o);
                        }
                        if($available_ship->h2 > 0)
                        {
                            $maxWithH2 = floor($availableResources[0][0]->h2 / $available_ship->h2);
                        }

                        $maxBuildable = min($maxWithFe, $maxWithLut, $maxWithCry, $maxWithH2o, $maxWithH2);

                        $shipAmount = floor($shipAmount);

                        if($shipAmount > $maxBuildable)
                        {
                            $shipAmount = $maxBuildable;
                        }

                        if($shipAmount <= 0)
                        {
                            return redirect('/shipyard/' . $planet_id);
                        }

                        $resourceArray[0] = new \stdClass();
                        $resourceArray[0]->fe = $availableResources[0][0]->fe - ($shipAmount * $available_ship->fe);
                        $resourceArray[0]->lut = $availableResources[0][0]->lut - ($shipAmount * $available_ship->lut);
                        $resourceArray[0]->cry = $availableResources[0][0]->cry - ($shipAmount * $available_ship->cry);
                        $resourceArray[0]->h2o = $availableResources[0][0]->h2o - ($shipAmount * $available_ship->h2o);
                        $resourceArray[0]->h2 = $availableResources[0][0]->h2 - ($shipAmount * $available_ship->h2);

                        $updateResources = Planet::setResourcesForPlanetById($planet_id, $resourceArray);
                        if($updateResources)
                        {
                            $success = Ship::setProductionProcess($planet_id, $shipAmount, $available_ship);
                            if($success)
                            {
                                return redirect('/shipyard/' . $planet_id);
                            } else {
                                dd('error starting process');
                            }
                        } else {
                            dd('error processing resources');
                        }
                    } else {
                        dd('not buildable');
                    }
                }
            }
        }

    }

    public function edit($planet_id)
    {
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);
        Controller::checkResearchProcesses($allUserPlanets);
        Controller::checkShipProcesses($allUserPlanets);
        $process = Planet::cancelShipProcess($planet_id);

        return redirect('/shipyard/' . $planet_id);
    }

    // helpers
    private static function populateBuildtimes($planet_id, $shipList)
    {
        $shipyard = Building::getOneByNameWithData($planet_id, "Schiffswerft");

        foreach($shipList as $key => $ship)
        {
            $base = $ship->initial_buildtime;
            $lvl = $shipyard ? $shipyard->level : 1;
            foreach(json_decode($ship->building_requirements) as $keyB => $req)
            {
                if($keyB == "Schiffswerft")
                {
                    if($lvl >= $req)
                    {
                        for($i = $req; $lvl > $i; $i++)
                        {
                            $base *= .9;
                        }
                    }
                }
            }

            if($base < 1)
            {
                // faster than a second? Set it back to 1 sec.
                $base = 1;
            }

            $shipList[$key]->current_buildtime = $base;

            // readable buildtime for FE
            $timestamp =  $base;
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
            $shipList[$key]->current_buildtime_readable = $bauzeit;
        }
    }
}
