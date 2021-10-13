<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use Illuminate\Support\Facades\DB;

class VacationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($timestamp)
    {
        return view('vacation.show', [
            'defaultPlanet' => session('default_planet'),
            'date' => date("H:m:i Y-m-d", $timestamp),
        ]);
    }

    public function deactivate()
    {
        $user = session()->get('user');
        DB::table('vacation')->where('vacation.user_id', $user->user_id)->update([
            'vacation_until' => null,
            'vacation_blocked_until' => date("Y-m-d H:i:s", now()->timestamp + (4*7*24*60*60)),
        ]);

        $start_planet = Profile::getStartPlanetByUserId($user->user_id);
        session(['default_planet' => $start_planet->start_planet]);
        return redirect('overview/' . $start_planet->start_planet);
    }

    public function enable()
    {
        $user = session()->get('user');
        DB::table('vacation')->where('vacation.user_id', $user->user_id)->update([
            'vacation_until' => date("Y-m-d H:i:s", now()->timestamp + (2*7*24*60*60)),
            'vacation_blocked_until' => null,
        ]);

        Auth::guard()->logout();
        return redirect('/login')->with(['enabled' => true]);
    }
}
