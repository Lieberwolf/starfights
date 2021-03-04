<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class ConstructionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::id();
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet[0]->start_planet]);
        return redirect('construction/' . $start_planet[0]->start_planet);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($planet_id, $building_id = false)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $planetInformation = Planet::getOneById($planet_id);
        $buildingList = Building::getAllAvailableBuildings($planet_id, $user_id);
        $currentConstruction = Planet::getPlanetaryBuildingProcess($planet_id);
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

                $buildingList[$key]->actual_buildtime =  floor($Grundzeit * $Modifikator1 * $Modifikator2);

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

        // check is a build process started
        if($building_id)
        {
            // selected building exists?
            $selectedBuilding = $buildingList->firstWhere('id', $building_id);
            if($selectedBuilding)
            {
                // check if selected building can be built (resources)
                if($planetaryResources[0][0]->fe >= $selectedBuilding->fe && $planetaryResources[0][0]->lut >= $selectedBuilding->lut && $planetaryResources[0][0]->cry >= $selectedBuilding->cry && $planetaryResources[0][0]->h2o >= $selectedBuilding->h2o && $planetaryResources[0][0]->h2 >= $selectedBuilding->h2)
                {
                    //start the build
                    $started = Building::startBuilding($selectedBuilding, $planet_id);
                    if($started)
                    {
                        // calculate new resources
                        $planetaryResources[0][0]->fe -= $selectedBuilding->fe;
                        $planetaryResources[0][0]->lut -= $selectedBuilding->lut;
                        $planetaryResources[0][0]->cry -= $selectedBuilding->cry;
                        $planetaryResources[0][0]->h2o -= $selectedBuilding->h2o;
                        $planetaryResources[0][0]->h2 -= $selectedBuilding->h2;
                        Planet::setResourcesForPlanetById($planet_id, $planetaryResources[0]);

                        return redirect('construction/' . $planet_id);
                    }

                }
            } else {
                // provide an error
                dd('error');
            }
        }

        if(count($planetaryResources)>0)
        {
            return view('construction.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'planetInformation' => $planetInformation,
                'availableBuildings' => $buildingList,
                'currentConstruction' => $currentConstruction
            ]);
        } else {
            return view('error.index');
        }

    }

    public function edit($planet_id)
    {
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $buildingList = Building::getAllAvailableBuildings($planet_id, $user_id);
        $currentConstruction = Planet::getPlanetaryBuildingProcess($planet_id);
        $selectedBuilding = $buildingList->firstWhere('id', $currentConstruction->building_id);

        // calculate new resources
        // todo: higher levels => higher cost, it only calculates level 1 costs
        $planetaryResources[0][0]->fe += $selectedBuilding->fe;
        $planetaryResources[0][0]->lut += $selectedBuilding->lut;
        $planetaryResources[0][0]->cry += $selectedBuilding->cry;
        $planetaryResources[0][0]->h2o += $selectedBuilding->h2o;
        $planetaryResources[0][0]->h2 += $selectedBuilding->h2;
        Planet::setResourcesForPlanetById($planet_id, $planetaryResources[0]);

        $canceled = Building::cancelBuilding($planet_id);
        if($canceled)
        {
            return redirect('construction/' . $planet_id);
        } else {
            dd('something broke');
        }
    }
}
