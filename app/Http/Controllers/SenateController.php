<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Messages as Messages;
use App\Models\User as User;

class SenateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = session()->get('user');$user_id = $user->user_id;
        $planet_id = session('default_planet');
        $allUserPlanets = session()->get('planets');
        $allBuildings = Building::all();

        return view('senate.show', [
            'defaultPlanet' => $planet_id,
            'activePlanet' => $planet_id,
            'allUserPlanets' => $allUserPlanets,
            'allBuildings' => $allBuildings,
            'senate' => true,
        ]);
    }
}
