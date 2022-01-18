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
        session(['default_planet' => $start_planet->start_planet]);
        return redirect('highscore/' . $start_planet->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = Planet::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $highscoreList = (array) User::getHighscoreList();

        if(count($planetaryResources)>0)
        {
            return view('highscore.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources,
                'planetaryStorage' => $planetaryResources,
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'highscoreList' => $highscoreList,
            ]);
        } else {
            return view('error.index');
        }
    }
}
