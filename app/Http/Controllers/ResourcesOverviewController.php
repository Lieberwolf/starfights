<?php

namespace App\Http\Controllers;

use App\Models\Planet as Planet;
use App\Models\Profile as Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourcesOverviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::id();
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet->start_planet]);
        return redirect('resources_overview/' . $start_planet->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Planet::getAllUserPlanets($user_id);

        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allResources = Planet::getUpdatedResourcesForAllPlanets($allUserPlanets);

        $sumAllResources = ['fe' => 0, 'lut' => 0, 'cry' => 0, 'h2o' => 0, 'h2' => 0];

        foreach($allResources as $resource) {
            $sumAllResources['fe'] += $resource['fe'];
            $sumAllResources['lut'] += $resource['lut'];
            $sumAllResources['cry'] += $resource['cry'];
            $sumAllResources['h2o'] += $resource['h2o'];
            $sumAllResources['h2'] += $resource['h2'];
        }

        Controller::checkAllProcesses($allUserPlanets);


        if(count($planetaryResources)>0)
        {
            return view('resources_overview.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources,
                'planetaryStorage' => $planetaryResources,
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'rates' => $planetaryResources,
                'allPlanetRates' => $allResources,
                'sumAllPlanetRates' => $sumAllResources,
            ]);
        } else {
            return view('error.index');
        }
    }
}
