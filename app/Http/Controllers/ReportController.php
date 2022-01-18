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

        if($user_id != null) {
        $planet_id = session('default_planet');
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = Planet::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);

        $report = Report::where('link', $report_uuid)->first();
        $report->attacker_fleet = json_decode($report->attacker_fleet);
        $report->defender_fleet = json_decode($report->defender_fleet);
        $report->defender_defense = json_decode($report->defender_defense);
        $report->resources = json_decode($report->resources);
        $report->attacker_name = User::where('id', $report->attacker_id)->first('username');
        $report->attacker_planet = Planet::where('id', $report->attacker_planet_id)->first();
        $report->defender_name = User::where('id', $report->defender_id)->first('username');
        $report->defender_planet = Planet::where('id', $report->defender_planet_id)->first();
        $report->planet_info = json_decode($report->planet_info);
        $report->planet_infrastructure = json_decode($report->planet_infrastructure);
        $report->defender_knowledge = json_decode($report->defender_knowledge);


        if($report->defender_fleet)
        {
            $fleetCheck = false;
            foreach($report->defender_fleet as $ship)
            {
                if($ship->amount > 0)
                {
                    $fleetCheck = true;
                }
            }

            if(!$fleetCheck)
            {
                $report->defender_fleet = false;
            }
        }

        if($report->defender_defense)
        {
            $defenseCheck = false;
            foreach($report->defender_defense as $turret)
            {
                if($turret->amount > 0)
                {
                    $defenseCheck = true;
                }
            }

            if(!$defenseCheck)
            {
                $report->defender_defense = false;
            }
        }



        return view('report.show', [
            'defaultPlanet' => session('default_planet'),
            'planetaryResources' => $planetaryResources,
            'planetaryStorage' => $planetaryResources,
            'allUserPlanets' => $allUserPlanets,
            'activePlanet' => $planet_id,
            'report' => $report
        ]);

        } else {
            return view('auth.login');
        }
    }
}
