<?php

namespace App\Http\Controllers;

use App\Models\Planet as Planet;
use App\Models\Profile as Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionOverviewController extends Controller
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
        return redirect('production_overview/' . $start_planet->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Planet::getAllUserPlanets($user_id);

        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allResources = Planet::getUpdatedResourcesForAllPlanets($allUserPlanets);

        $sumAllResources = ['rate_fe' => 0, 'rate_lut' => 0, 'rate_cry' => 0, 'rate_h2o' => 0, 'rate_h2' => 0];

        foreach($allResources as $resource) {
            $sumAllResources['rate_fe'] += $resource['rate_fe'];
            $sumAllResources['rate_lut'] += $resource['rate_lut'];
            $sumAllResources['rate_cry'] += $resource['rate_cry'];
            $sumAllResources['rate_h2o'] += $resource['rate_h2o'];
            $sumAllResources['rate_h2'] += $resource['rate_h2'];
        }

        Controller::checkAllProcesses($allUserPlanets);


        if(count($planetaryResources)>0)
        {
            return view('production_overview.show', [
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
