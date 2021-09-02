<?php

namespace App\Http\Controllers;

use App\Models\Alliances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class SearchController extends Controller
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
        return redirect('search/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $searchResult = false;

        if(session('return'))
        {
            $searchResult = session('return');
        }


        if(count($planetaryResources)>0)
        {
            return view('search.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'searchResult' => $searchResult,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function mode($planet_id)
    {
        $data = request()->validate([
            'mode' => 'required|max:1|string',
            'term' => 'required|min:3|string'
        ]);

        switch ($data["mode"])
        {
            case 'p':
                $result = Profile::where('nickname','like','%'.$data["term"].'%')->take(20)->get();
                break;

            case 'a':
                $result = Alliances::where('alliance_name','like','%'.$data["term"].'%')->take(20)->get();
                break;
            case 't':
                $result = Alliances::where('alliance_tag','like','%'.$data["term"].'%')->take(20)->get();
                break;
            case 'n':
                $result = Planet::where('planet_name','like','%'.$data["term"].'%')->take(20)->get();
                break;
            default:
                return redirect('/search/' . $planet_id);
                break;
        }

        $return = new \stdClass();
        $return->result = count($result) > 0 ? $result : false;
        $return->term = $data["term"];
        $return->mode = $data["mode"];

        return redirect('/search/' . $planet_id)->with('return', $return);

    }
}
