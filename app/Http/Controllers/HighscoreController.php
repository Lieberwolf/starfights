<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class HighscoreController extends Controller
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
        return redirect('highscore/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $users = User::getAllUserProfiles();

        $list = [];
        foreach($users as $key => $user) {
            $planets = Planet::getAllUserPlanets($user->user_id);
            $allPlanetPoints = Planet::getAllPlanetaryPointsByIds($planets);
            $allResearchPoints = Research::getAllUserResearchPointsByUserId($user->user_id);

            $list[$key] = $user;
            $list[$key]->planetPoints = $allPlanetPoints;
            $list[$key]->researchPoints = $allResearchPoints;
            $list[$key]->totalPoints = $allPlanetPoints + $allResearchPoints;
        }

        usort($list, function($a, $b) {
            if($a->totalPoints == $b->totalPoints){ return 0 ; }
            return ($a->totalPoints < $b->totalPoints) ? 1 : -1;
        });


        if(count($planetaryResources)>0)
        {
            return view('highscore.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'users' => $list,
            ]);
        } else {
            return view('error.index');
        }
    }
}
