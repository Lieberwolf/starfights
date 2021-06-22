<?php

namespace App\Http\Controllers;

use App\Models\Alliances as Alliance;
use App\Models\Alliances;
use App\Models\Messages as Messages;
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
        $alliance = Alliance::getAllianceForUser($user_id);

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
        $profileData = Profile::getUsersProfileById($user_id);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $applications = false;
        if(!is_numeric($alliance_id)) {
            return redirect('/overview/' . $planet_id);
        } else {
            $alliance = Alliance::getAllianceForUser($user_id);
            if($alliance_id == $alliance->alliance_id) {
                $alliance->own = true;
                $applications = Alliance::getUserApplications($alliance_id);
            } else {
                // get foreign alliance data
                $alliance = Alliance::getAllianceByAllyId($alliance_id);

                if($alliance == null && $alliance_id == 0)
                {
                    $alliance = new \stdClass();
                    $alliance->id = null;
                }

                if($alliance) {
                    $alliance->own = false;
                } else {
                    return redirect('/overview/' . $planet_id);
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
                'userData' => $profileData,
                'applications' => $applications,
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
        $alliance = Alliance::getAllianceForUser($user_id);
        if($alliance->alliance_id)
        {
            return redirect('/overview/' . $planet_id);
        }

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
        $allianceData = Alliance::getUsersInAlliance($alliance_id);

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
            'name' => 'required|max:24|min:3|unique:alliances',
            'tag' => 'required|max:5|min:3|unique:alliances'
        ]);

        $founded = Alliance::foundAlliance($data, Auth::id());

        if($founded)
        {
            $updated = Alliance::setAllianceToFounder(Auth::id());
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

    public function send($planet_id, $alliance_id)
    {
        $user_id = Auth::id();
        $profile = Profile::getUsersProfileById($user_id);
        $alliance = Alliance::getAllianceForUser($user_id);
        if($alliance_id == $alliance->alliance_id) {
            // this is the users alliance
            $data = request()->validate([
                'message' => 'required|min:3',
            ]);

            // messages object:
            /*
            {
                user_id: integer,
                user_name: string,
                message: string,
                date: timestamp
            }

             */
            $messages = json_decode($alliance->alliance_messages);
            if($messages)
            {
                $message = new \stdClass();
                $message->user_id = $user_id;
                $message->user_name = $profile->nickname;
                $message->message = $data["message"];
                $message->date = now()->timestamp;
            } else {
                $messages = [];
                $message = new \stdClass();
                $message->user_id = $user_id;
                $message->user_name = $profile->nickname;
                $message->message = $data["message"];
                $message->date = now()->timestamp;
            }

            array_push($messages, $message);
            Alliance::saveMessages($alliance_id, json_encode($messages));
            return redirect('/alliance/' . $planet_id);

        } else {
            return redirect('/alliance/' . $planet_id);
        }
    }

    public function apply($planet_id, $alliance_id)
    {
        $user_id = Auth::id();
        // save the alliance id as *-1 in users profile to mark him as already applied somewhere
        $profile = Profile::getUsersProfileById($user_id);
        $profile->alliance_application = $alliance_id;
        $profile->save();

        return redirect('/alliance/' . $planet_id . '/' . $alliance_id)->with('status', 'Bewerbung eingereicht');
    }

    public function accept($planet_id, $alliance_id, $user_id)
    {
        $profile = Profile::getUsersProfileById($user_id);
        $profile->alliance_application = null;
        $profile->alliance_id = $alliance_id;
        $profile->save();
        $alliance = Alliance::getAllianceByAllyid($alliance_id);

        // emit system message to user
        $message = [
            'user_id' => 0,
            'receiver_id' => $user_id,
            'subject' => 'Bewerbung bei ' . $alliance->alliance_name,
            'message' => 'Deine Bewerbung bei ' . $alliance->alliance_name . ' wurde akzeptiert.'
        ];
        Messages::create($message);

        return redirect('/alliance/' . $planet_id . '/' . $alliance_id)->with('status', 'Bewerbung von ' . $profile->nickname . ' akzeptiert.');

    }

    public function decline($planet_id, $alliance_id, $user_id)
    {
        $profile = Profile::getUsersProfileById($user_id);
        $profile->alliance_application = null;
        $profile->save();
        $alliance = Alliance::getAllianceByAllyid($alliance_id);

        // emit system message to user
        $message = [
            'user_id' => 0,
            'receiver_id' => $user_id,
            'subject' => 'Bewerbung bei ' . $alliance->alliance_name,
            'message' => 'Deine Bewerbung bei ' . $alliance->alliance_name . ' wurde abgelehnt.'
        ];
        Messages::create($message);

        return redirect('/alliance/' . $planet_id . '/' . $alliance_id)->with('status', 'Bewerbung von ' . $profile->nickname . ' abgelehnt.');

    }
}
