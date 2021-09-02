<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\Planet as Planet;
use App\Models\Profile as Profile;
use App\Models\Ship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipsOverviewController extends Controller
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
        return redirect('ships/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $allShips = Ship::all();

        foreach($allUserPlanets as $planet)
        {
            $planet->fleet = false;
            $planet->fleetRaw = Fleet::getShipsAtPlanet($planet->id);
            if($planet->fleetRaw)
            {
                $planet->fleet = json_decode($planet->fleetRaw->ship_types);
            }
        }

        if(count($planetaryResources)>0)
        {
            return view('ships.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'allShips' => $allShips,
            ]);
        } else {
            return view('error.index');
        }
    }
}
