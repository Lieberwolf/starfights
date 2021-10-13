<?php

namespace App\Http\Controllers;

use App\Models\Planet as Planet;
use App\Models\Profile as Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = session()->get('user');$user_id = $user->user_id;
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet->start_planet]);
        return redirect('notice/' . $start_planet->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $notice = DB::table('notices')->where('user_id', $user_id)->first();

        if($notice)
        {
            $notice = json_decode($notice->content);
        }

        if(count($planetaryResources)>0)
        {
            return view('notice.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'notice' => $notice,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function edit($planet_id)
    {
        $user = session()->get('user');$user_id = $user->user_id;
        $notice = DB::table('notices')->where('user_id', $user_id)->first();
        $data = request()->validate([
            'notice' => 'required|string'
        ]);

        $temp = new \stdClass();
        $temp->content = $data["notice"];

        if($notice)
        {
            DB::table('notices')->where('user_id', $user_id)->update(['content' => json_encode($temp)]);

        } else {
            DB::table('notices')->insert([
                'user_id' => $user_id,
                'content' => json_encode($temp),
            ]);
        }

        return redirect('/notice/' . $planet_id);

    }
}
