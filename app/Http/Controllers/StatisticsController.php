<?php

namespace App\Http\Controllers;

use App\Models\Planet as Planet;
use App\Models\Profile as Profile;
use App\Models\Statistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class StatisticsController extends Controller
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
        return redirect('statistics/' . $start_planet[0]->start_planet);
    }

    public function showUser($planet_id, $users_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        if(!is_numeric($users_id))
        {
            $users_id = Auth::id();
        }
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $stats = Statistics::where('statistics.user_id', $users_id)->leftJoin('profiles as p','p.user_id', '=', 'statistics.user_id')->first();

        if(count($planetaryResources)>0)
        {
            return view('statistics.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'stats' => $stats,
                'mode' => 'u'
            ]);
        } else {
            return view('error.index');
        }
    }

    public function showAlly($planet_id, $ally_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        if(!is_numeric($ally_id))
        {
            return redirect('/overview/' . $planet_id);
        }
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $stats = Statistics::where('statistics.alliance_id', $ally_id)->leftJoin('alliances as a','a.id', '=', 'statistics.alliance_id')->first();

        if(count($planetaryResources)>0)
        {
            return view('statistics.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'stats' => $stats,
                'mode' => 'a'
            ]);
        } else {
            return view('error.index');
        }
    }
}
