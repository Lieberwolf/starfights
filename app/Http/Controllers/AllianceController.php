<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;


class AllianceController extends Controller
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
        return redirect('alliance/' . $start_planet[0]->start_planet);
    }

    public function redirect($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $profile = new Profile();
        $alliance = $profile->getAllianceForUser($user_id);

        if(!$alliance->alliance_id) {
            return redirect('/alliance/' . $planet_id . '/0');
        } else {
            return redirect('/alliance/' . $planet_id . '/' . $alliance->alliance_id);
        }
    }

    public function show($planet_id, $alliance_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $profile = new Profile();
        $profileData = Profile::getUsersProfileById($user_id);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        if(!is_numeric($alliance_id)) {
            return redirect('/overview/' . $planet_id);
        } else {
            $alliance = $profile->getAllianceForUser($user_id);
            if($alliance_id == $alliance->alliance_id) {
                $alliance->own = true;
            } else {
                // get foreign alliance data
                $alliance = $profile->getAllianceByAllyId($alliance_id);
                if($alliance) {
                    $alliance->own = false;
                }
            }
        }


        if(count($planetaryResources)>0)
        {
            return view('alliance.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'alliance' => $alliance,
                'userData' => $profileData
            ]);
        } else {
            return view('error.index');
        }
    }

    public function option($planet_id)
    {
        $data = request()->validate([
            'target' => 'required'
        ]);

        if($data["target"] == 'new')
        {
            // if option is new, redirect to founding page
            return redirect('/alliance/' . $planet_id . '/found');
        } else {
            // else redirect direct to search page
            return redirect('/search/' . $planet_id);
        }
    }

    public function found($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);

        if(count($planetaryResources)>0)
        {
            return view('alliance.found', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function memberslist($planet_id, $alliance_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $allianceData = Profile::getUsersInAlliance($alliance_id);

        $list = [];
        foreach($allianceData->members as $key => $user) {
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
            return view('alliance.memberslist', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'allianceData' => $allianceData,
                'members' => $list
            ]);
        } else {
            return view('error.index');
        }
    }

    public function founding($planet_id)
    {
        $data = request()->validate([
            'name' => 'required|max:24|min:3',
            'tag' => 'required|max:5|min:3'
        ]);

        $founded = Profile::foundAlliance($data, Auth::id());

        if($founded)
        {
            $updated = Profile::setAllianceToFounder(Auth::id());
            if($updated)
            {
                return redirect('/alliance/' . $planet_id);
            } else {
                return view('error.index');
            }
        } else {
            return view('error.index');
        }
    }
}
