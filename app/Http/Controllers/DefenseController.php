<?php

namespace App\Http\Controllers;

use App\Models\Building as Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Turret as Turret;

class DefenseController extends Controller
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
        return redirect('defense/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $nextTurretIn = Controller::checkTurretProcesses($allUserPlanets);
        $turretList = Turret::getAllAvailableTurrets($user_id, $planet_id);
        $currentTurrets = Planet::getPlanetaryTurretProcess($planet_id);
        $planetInformation = Planet::getOneById($planet_id);

        $prevPlanet = false;
        foreach($allUserPlanets as $key => $planet)
        {
            if($planet->id == $planet_id)
            {
                if(!empty($allUserPlanets[$key-1]))
                {
                    $prevPlanet = $allUserPlanets[$key-1];
                } else {
                    $prevPlanet = $allUserPlanets[count($allUserPlanets)-1];
                }
            }
        }

        $nextPlanet = false;
        foreach($allUserPlanets as $key => $planet)
        {
            if($planet->id == $planet_id)
            {
                if(!empty($allUserPlanets[$key+1]))
                {
                    $nextPlanet = $allUserPlanets[$key+1];
                } else {
                    $nextPlanet = $allUserPlanets[0];
                }
            }
        }

        if(is_bool($nextTurretIn) || $nextTurretIn == null || $currentTurrets == null)
        {
            $currentTurrets = false;
        } else {
            $currentTurrets->nextTurretIn = $nextTurretIn;
        }

        self::populateBuildtimes($planet_id, $turretList);

        foreach($turretList as $key => $turret)
        {
            $maxWithFe = true;
            $maxWithLut = true;
            $maxWithCry = true;
            $maxWithH2o = true;
            $maxWithH2 = true;

            if($turret->fe > 0)
            {
                $maxWithFe = floor($planetaryResources[0]->fe / $turret->fe);
            }
            if($turret->lut > 0)
            {
                $maxWithLut = floor($planetaryResources[0]->lut / $turret->lut);
            }
            if($turret->cry > 0)
            {
                $maxWithCry = floor($planetaryResources[0]->cry / $turret->cry);
            }
            if($turret->h2o > 0)
            {
                $maxWithH2o = floor($planetaryResources[0]->h2o / $turret->h2o);
            }
            if($turret->h2 > 0)
            {
                $maxWithH2 = floor($planetaryResources[0]->h2 / $turret->h2);
            }

            $maxBuildable = min($maxWithFe, $maxWithLut, $maxWithCry, $maxWithH2o, $maxWithH2);
            $turretList[$key]->max_amount = $maxBuildable;
        }

        if(count($planetaryResources)>0)
        {
            return view('defense.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'turretList' => $turretList,
                'currentTurrets' => $currentTurrets,
                'planetInformation' => $planetInformation,
                'prevPlanet' => $prevPlanet,
                'nextPlanet' => $nextPlanet,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function build($planet_id)
    {
        $data = request()->all();
        $turretsToBuild = $data["turret"];
        $user_id = Auth::id();
        $availableResources = Planet::getResourcesForPlanet($planet_id);
        $availableTurrets = Turret::getAllAvailableTurrets($user_id, $planet_id);
        $defensePlatform = Building::getOneByNameWithData($planet_id, "Verteidigungsstation");

        self::populateBuildtimes($planet_id, $availableTurrets);

        foreach($turretsToBuild as $key => $turretAmount)
        {
            foreach($availableTurrets as $keyB => $available_turret)
            {
                if($key == $available_turret->turret_name)
                {
                    if($available_turret->buildable)
                    {
                        // ship can theoretically be built
                        // check resources to get lowest max amount
                        $maxWithFe = 99999999;
                        $maxWithLut = 99999999;
                        $maxWithCry = 99999999;
                        $maxWithH2o = 99999999;
                        $maxWithH2 = 99999999;

                        if($available_turret->fe > 0)
                        {
                            $maxWithFe = floor($availableResources[0]->fe / $available_turret->fe);
                        }
                        if($available_turret->lut > 0)
                        {
                            $maxWithLut = floor($availableResources[0]->lut / $available_turret->lut);
                        }
                        if($available_turret->cry > 0)
                        {
                            $maxWithCry = floor($availableResources[0]->cry / $available_turret->cry);
                        }
                        if($available_turret->h2o > 0)
                        {
                            $maxWithH2o = floor($availableResources[0]->h2o / $available_turret->h2o);
                        }
                        if($available_turret->h2 > 0)
                        {
                            $maxWithH2 = floor($availableResources[0]->h2 / $available_turret->h2);
                        }

                        $maxBuildable = min($maxWithFe, $maxWithLut, $maxWithCry, $maxWithH2o, $maxWithH2);

                        $turretAmount = floor($turretAmount);

                        if($turretAmount > $maxBuildable)
                        {
                            $turretAmount = $maxBuildable;
                        }

                        if($turretAmount <= 0)
                        {
                            return redirect('/defense/' . $planet_id);
                        }

                        $resourceArray = new \stdClass();
                        $resourceArray->fe = $availableResources[0]->fe - ($turretAmount * $available_turret->fe);
                        $resourceArray->lut = $availableResources[0]->lut - ($turretAmount * $available_turret->lut);
                        $resourceArray->cry = $availableResources[0]->cry - ($turretAmount * $available_turret->cry);
                        $resourceArray->h2o = $availableResources[0]->h2o - ($turretAmount * $available_turret->h2o);
                        $resourceArray->h2 = $availableResources[0]->h2 - ($turretAmount * $available_turret->h2);

                        $updateResources = Planet::setResourcesForPlanetById($planet_id, $resourceArray);
                        if($updateResources)
                        {
                            $success = Turret::setProductionProcess($planet_id, $turretAmount, $available_turret);
                            if($success)
                            {
                                return redirect('/defense/' . $planet_id);
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
        $process = Planet::cancelTurretProcess($planet_id);
        if($process) {
            return redirect('/defense/' . $planet_id);
        } else {
            dd("Something wrong :(");
        }
    }

    // helpers
    private static function populateBuildtimes($planet_id, $turretList)
    {
        $defensePlatform = Building::getOneByNameWithData($planet_id, "Verteidigungsstation");

        foreach($turretList as $key => $turret)
        {
            $base = $turret->initial_buildtime;
            $lvl = $defensePlatform ? $defensePlatform->level : 1;
            foreach(json_decode($turret->building_requirements) as $keyB => $req)
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

            $turretList[$key]->current_buildtime = $base;

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
            $turretList[$key]->current_buildtime_readable = $bauzeit;
        }
    }
}
