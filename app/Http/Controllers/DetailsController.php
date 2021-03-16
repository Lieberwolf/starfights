<?php

namespace App\Http\Controllers;

use App\Models\Planet as Planet;
use App\Models\Profile as Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailsController extends Controller
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
        return redirect('defense/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkAllProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $planetInfo = Planet::getOneById($planet_id);

        if(count($planetaryResources)>0)
        {
            return view('details.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'planetInfo' => $planetInfo,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function name($planet_id)
    {
        $planet = Planet::getOneById($planet_id);
        $data = request()->validate([
            'planet_name' => 'required']);
        $planet->planet_name = $data["planet_name"];
        $planet->save();

        return redirect('/details/' . $planet_id);
    }

    public function image($planet_id)
    {
        $planet = Planet::getOneById($planet_id);
        $data = request()->validate([
            'image' => 'required|url']);
        $planet->image = $data["image"];
        $planet->save();

        return redirect('/details/' . $planet_id);
    }
}
