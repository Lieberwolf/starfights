<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\User as User;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
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
        return redirect('settings/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $user = User::where('id', $user_id)->first();

        if(count($planetaryResources)>0)
        {
            return view('settings.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'user' => $user,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function updateE($planet_id)
    {
        $data = request()->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'confirmed'],
        ]);
        User::where('id', Auth::id())->update([
            'email' => $data["email"]
        ]);

        return redirect('/settings/' . $planet_id)->with('status', 'E-Mail wurde aktualisiert.');
    }

    public function updateP($planet_id)
    {
        $data = request()->validate([
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);
        User::where('id', Auth::id())->update([
            'password' => Hash::make($data['password'])
        ]);

        return redirect('/settings/' . $planet_id)->with('status', 'Passwort wurde aktualisiert.');
    }
}
