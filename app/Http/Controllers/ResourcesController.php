<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class ResourcesController extends Controller
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
        return redirect('resources/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        $planetInfo = Planet::getOneById($planet_id);
        Controller::checkAllProcesses($allUserPlanets);
        $stats = Controller::calcResourceRatesForPlanet($planet_id);
        $resourceBuildings = [];

        foreach($stats[0] as $building)
        {
            if($building->prod_fe > 0 || $building->prod_lut > 0 || $building->prod_cry> 0 || $building->prod_h2o > 0 || $building->prod_h2 > 0)
            {
                if($building->infrastructure != null)
                {
                    $resourceBuildings[] = $building;
                }
            }
        }

        if(count($planetaryResources)>0)
        {
            return view('resources.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'resourceBuildings' => $resourceBuildings,
                'rates' => $planetaryResources[0],
                'storage' => $planetaryResources[1],
                'planetInfo' => $planetInfo,
                'bonusValues' => $stats[1],
            ]);
        } else {
            return view('error.index');
        }
    }
}
