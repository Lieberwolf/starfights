<?php

namespace App\Http\Controllers;

use App\Models\Alliances;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\User as User;
use Illuminate\Support\Facades\DB;
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

    public function delete()
    {
        $data = request()->validate([
            'delete' => ['required']
        ]);

        $planets = $this->getAllUserPlanets(Auth::id());

        foreach($planets as $planet) {
            // building_processes
            DB::table('building_process')->where('planet_id', $planet->id)->delete();
            // defenses
            DB::table('defenses')->where('planet_id', $planet->id)->delete();
            // fleets outgoing or stationary
            DB::table('fleets')->where('planet_id', $planet->id)->delete();
            // fleets attacking
            DB::table('fleets')->where('target', $planet->id)->update(['arrived' => 1]);
            // infrastructures
            DB::table('infrastructures')->where('planet_id', $planet->id)->delete();
            // research_process
            DB::table('research_process')->where('planet_id', $planet->id)->delete();
            // ships_process
            DB::table('ships_process')->where('planet_id', $planet->id)->delete();
            // turrets_process
            DB::table('turrets_process')->where('planet_id', $planet->id)->delete();
        }

        $alliance = Alliances::getAllianceForUser(Auth::id());
        if($alliance && $alliance->founder_id == Auth::id()) {
            // statistics
            DB::table('statistics')->where('alliance_id', $alliance->alliance_id)->delete();
            DB::table('alliances')->where('id', $alliance->alliance_id)->delete();
            Profile::where('alliance_id', $alliance->alliance_id)->update(['alliance_id' => null]);
            Profile::where('alliance_application', $alliance->alliance_id)->update(['alliance_application' => null]);
        }

        // knowledge
        DB::table('knowledge')->where('user_id', Auth::id())->delete();
        // messages
        DB::table('messages')->where('receiver_id', Auth::id())->delete();
        // notices
        DB::table('notices')->where('user_id', Auth::id())->delete();
        // profiles
        DB::table('profiles')->where('user_id', Auth::id())->delete();
        // statistics
        DB::table('statistics')->where('user_id', Auth::id())->delete();
        // users
        DB::table('users')->where('id', Auth::id())->delete();

        return redirect('/');
    }
}
