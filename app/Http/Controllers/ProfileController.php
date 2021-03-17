<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::id();
        return redirect('profile/' . $user_id);
    }

    public function show($user_id)
    {
        // update session with new planet id
        $planet_id = session('default_planet');

        $userInformation = Profile::where('user_id', $user_id)->first();
        $planetsList = Controller::getAllUserPlanetsWithData($user_id);
        $userInformation->planetsList = $planetsList;

        if($user_id == Auth::id())
        {
            $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
            // my own profile
            $proof = true;
            $allUserPlanets = Controller::getAllUserPlanets($user_id);
            Controller::checkAllProcesses($allUserPlanets);
        } else {
            $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, Auth::id());
            // other ones profile
            $proof = false;
            $allUserPlanets = Controller::getAllUserPlanets(Auth::id());
            Controller::checkAllProcesses($allUserPlanets);
        }

        foreach($userInformation->planetsList as $key => $planet)
        {
            $userInformation->planetsList[$key]->points = Planet::getPlanetaryPointsById($planet->id);
        }

        $planets = Planet::getAllUserPlanets($user_id);
        $allPlanetPoints = Planet::getAllPlanetaryPointsByIds($planets);
        $allResearchPoints = Research::getAllUserResearchPointsByUserId($user_id);

        $totalPoints = $allPlanetPoints + $allResearchPoints;

        if(count($planetaryResources)>0)
        {
            return view('profile.show', [
                'defaultPlanet' => $planet_id,
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'ownProfile' => $proof,
                'profileData' => $userInformation,
                'totalPoints' => $totalPoints,
            ]);
        } else {
            return view('error.index');
        }
    }
}
