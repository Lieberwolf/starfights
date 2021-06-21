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
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $planetInformation = Planet::getOneById($planet_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $buildingListRaw = Building::getAllAvailableBuildings($planet_id, $user_id);
        $currentConstruction = Planet::getPlanetaryBuildingProcess($planet_id);
        $buildingList = Controller::factorizeBuildings($buildingListRaw);

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

        $prevPlanet = false;
        foreach($allUserPlanets as $key => $planet)
        {
            if($planet->id == $planet_id)
            {
                if(!empty($allUserPlanets[$key-1]))
                {
                    $prevPlanet = $allUserPlanets[$key-1];
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
                }
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
                'currentConstruction' => $currentConstruction,
                'prevPlanet' => $prevPlanet,
                'nextPlanet' => $nextPlanet,

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
        $buildingListFactorized = Controller::factorizeBuildings($buildingList);
        $levelResources = $buildingListFactorized->first(function($value) use ($currentConstruction) {
            return $value->id == $currentConstruction->building_id;
        });

        // calculate new resources
        // todo: higher levels => higher cost, it only calculates level 1 costs
        $planetaryResources[0][0]->fe += $levelResources->fe;
        $planetaryResources[0][0]->lut += $levelResources->lut;
        $planetaryResources[0][0]->cry += $levelResources->cry;
        $planetaryResources[0][0]->h2o += $levelResources->h2o;
        $planetaryResources[0][0]->h2 += $levelResources->h2;
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
