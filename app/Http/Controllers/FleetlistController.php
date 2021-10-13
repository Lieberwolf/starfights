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
        $user = session()->get('user');$user_id = $user->user_id;
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet->start_planet]);
        return redirect('fleetlist/' . $start_planet->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $fleetsOnMission = Fleet::getFleetsOnMission($allUserPlanets);

        if(count($planetaryResources)>0)
        {
            return view('fleetlist.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'fleetsOnMission' => $fleetsOnMission,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function edit($planet_id, $fleet_id)
    {
        $fleet = Fleet::where('id', $fleet_id)->where('planet_id', $planet_id)->first();
        $runningSeconds = now()->timestamp - strtotime($fleet->departure);

        $fleet->arrival = date('Y-m-d H:i:s',strtotime($fleet->departure) + $runningSeconds);
        $fleet->mission = 0;
        $fleet->save();

        return redirect('/fleetlist/' . $planet_id);
    }
}
