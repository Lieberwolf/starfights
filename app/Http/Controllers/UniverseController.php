<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class UniverseController extends Controller
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
        return redirect('universe/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id, $galaxy = false, $system = false)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        if($galaxy == null)
        {
            $galaxy = 1;
        }

        if($system == null)
        {
            $system = 1;
        }

        $planets = (new Planet)->universePart($galaxy, $system);

        foreach($planets as $key => $planet)
        {
            $planet->points = Planet::getPlanetaryPointsById($planet->id);
        }

        if(count($planetaryResources)>0)
        {
            return view('universe.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'planets' => $planets
            ]);
        } else {
            return view('error.index');
        }
    }
}
