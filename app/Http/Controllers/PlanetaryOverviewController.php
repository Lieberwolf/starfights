<?php

namespace App\Http\Controllers;

use App\Models\Planet as Planet;
use App\Models\Profile as Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanetaryOverviewController extends Controller
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
        return redirect('planetary/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $planetInfo = Planet::getOneById($planet_id);
        $allBuildProcesses = Planet::getAllPlanetaryBuildingProcess($allUserPlanets);
        $allResearchProcesses = Planet::getAllPlanetaryResearchProcess($allUserPlanets, $user_id);

        foreach($allUserPlanets as $planet)
        {
            $tempShips = Controller::checkShipProcesses($allUserPlanets);
            $planet->nextShip = false;
            foreach($tempShips as $ship)
            {
                if($ship->planet == $planet->id)
                {
                    $planet->nextShip = $ship;
                }
            }
            $tempTurrets = Controller::checkTurretProcesses($allUserPlanets);
            $planet->nextTurret = false;
            foreach($tempTurrets as $turret)
            {
                if($turret->planet == $planet->id)
                {
                    $planet->nextTurret = $turret;
                }
            }
        }
        if(count($planetaryResources)>0)
        {
            return view('planetary.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources,
                'planetaryStorage' => $planetaryResources,
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'planetInfo' => $planetInfo,
                'buildings' => $allBuildProcesses,
                'research' => $allResearchProcesses,
            ]);
        } else {
            return view('error.index');
        }
    }
}
