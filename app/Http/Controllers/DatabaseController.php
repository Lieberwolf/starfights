<?php

namespace App\Http\Controllers;


use App\Models\Building;
use App\Models\Research;
use App\Models\Ship;
use App\Models\Turret;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class DatabaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = session()->get('user');$user_id = $user->user_id;
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet->start_planet]);
        return redirect('database/' . $start_planet->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);

        if(count($planetaryResources)>0)
        {
            return view('database.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function buildings($planet_id)
    {
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $allBuildings = Building::all();
        if(count($planetaryResources)>0)
        {
            return view('database.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'data' => $allBuildings,
                'isBuilding' => true,
                'isResearch' => false,
                'isShips' => false,
                'isDefense' => false,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function research($planet_id)
    {
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $allResearch = Research::all();

        if(count($planetaryResources)>0)
        {
            return view('database.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'data' => $allResearch,
                'isBuilding' => false,
                'isResearch' => true,
                'isShips' => false,
                'isDefense' => false,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function ships($planet_id)
    {
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $allShips = Ship::all();

        if(count($planetaryResources)>0)
        {
            return view('database.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'data' => $allShips,
                'isBuilding' => false,
                'isResearch' => false,
                'isShips' => true,
                'isDefense' => false,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function turrets($planet_id)
    {
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $allTurrets = Turret::all();

        if(count($planetaryResources)>0)
        {
            return view('database.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'data' => $allTurrets,
                'isBuilding' => false,
                'isResearch' => false,
                'isShips' => false,
                'isDefense' => true,
            ]);
        } else {
            return view('error.index');
        }
    }
}
