<?php

namespace App\Http\Controllers;

use App\Models\Fleet as Fleet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class FleetlistController extends Controller
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
        return redirect('fleetlist/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        Controller::checkResearchProcesses($allUserPlanets);
        Controller::checkShipProcesses($allUserPlanets);
        Controller::checkFleetProcesses($allUserPlanets);
        $fleetsOnMission = Fleet::getFleetsOnMission($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        if(count($planetaryResources)>0)
        {
            return view('fleetlist.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'fleetsOnMission' => $fleetsOnMission,
            ]);
        } else {
            return view('error.index');
        }
    }
}
