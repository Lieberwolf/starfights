<?php

namespace App\Http\Controllers;

use App\Models\Alliances;
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

        $userInformation = Profile::where('profiles.user_id', $user_id)
            ->leftJoin('vacation as v', 'v.user_id', '=', 'profiles.user_id')
            ->first();
        $planetsList = Controller::getAllUserPlanetsWithData($user_id);
        $userInformation->planetsList = $planetsList;
        $alliance = Alliances::getAllianceForUser($user_id);

        if($user_id == Auth::id())
        {
            $planetaryResources = Planet::getResourcesForPlanet($planet_id);
            // my own profile
            $proof = true;
            $allUserPlanets = Planet::getAllUserPlanets($user_id);
            Controller::checkAllProcesses($allUserPlanets);
        } else {
            $planetaryResources = Planet::getResourcesForPlanet($planet_id);
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
                'planetaryResources' => $planetaryResources,
                'planetaryStorage' => $planetaryResources,
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'ownProfile' => $proof,
                'profileData' => $userInformation,
                'totalPoints' => $totalPoints,
                'alliance' => $alliance
            ]);
        } else {
            return view('error.index');
        }
    }
}
