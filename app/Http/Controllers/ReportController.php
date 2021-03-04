<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\User as User;

class ReportController extends Controller
{
    public function index()
    {
        return redirect('/overview');
    }

    public function show($report_uuid)
    {
        $user_id = Auth::id();

        $planet_id = session('default_planet');

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $report = Report::where('link', $report_uuid)->first();
        $report->attacker_fleet = json_decode($report->attacker_fleet);
        $report->defender_fleet = json_decode($report->defender_fleet);
        $report->resources = json_decode($report->resources);
        $report->attacker_name = User::where('id', $report->attacker_id)->first('username');
        $report->attacker_planet = Planet::where('id', $report->attacker_planet_id)->first();
        $report->defender_name = User::where('id', $report->defender_id)->first('username');
        $report->defender_planet = Planet::where('id', $report->defender_planet_id)->first();

        return view('report.show', [
            'defaultPlanet' => session('default_planet'),
            'planetaryResources' => $planetaryResources[0][0],
            'planetaryStorage' => $planetaryResources[1],
            'allUserPlanets' => $allUserPlanets,
            'activePlanet' => $planet_id,
            'report' => $report
        ]);
    }
}
