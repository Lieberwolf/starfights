<?php

namespace App\Http\Controllers;

use App\Models\Defense;
use App\Models\Research;
use App\Models\Ship;
use App\Models\Turret;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class SimulationController extends Controller
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
        return redirect('simulation/' . $start_planet->start_planet);
    }

    public function show(Request $request, $planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $allShips = Ship::all();
        $allResearch = Research::getAllResearchesWithEffect();
        $allDefense = Turret::all();
        $report = session('report');
        $request->session()->forget('report');

        if($report)
        {
            foreach($allShips as $key => $ship)
            {
                foreach($report[0]["ship"] as $attackerShip)
                {
                    if($ship->id == $attackerShip->ship_id)
                    {
                        $attackerShip->ship_name = $ship->ship_name;
                        $allShips[$key]->attackerAmount = $attackerShip->amount;
                    }
                }
                foreach($report[1]["ship"] as $defenderShip)
                {
                    if($ship->id == $defenderShip->ship_id)
                    {
                        $defenderShip->ship_name = $ship->ship_name;
                        $allShips[$key]->defenderAmount = $defenderShip->amount;
                    }
                }
            }
            foreach($allDefense as $key => $turret)
            {
                foreach($report[1]["turrets"] as $defenderTurret)
                {
                    if($turret->id == $defenderTurret->turret_id)
                    {
                        $defenderTurret->turret_name = $turret->turret_name;
                        $turret->defenderAmount = $defenderTurret->newAmount;
                        $turret->amount = $defenderTurret->amount;
                    }
                }
            }
            foreach($allResearch as $key => $research)
            {
                foreach($report[0]["research"] as $attackerResearch)
                {
                    if($attackerResearch->research_id == $research->research_id)
                    {
                        $research->attLevel = $attackerResearch->level;
                    }
                }
                foreach($report[1]["research"] as $defenderResearch)
                {
                    if($defenderResearch->research_id == $research->research_id)
                    {
                        $research->defLevel = $defenderResearch->level;
                    }
                }
            }
        } else {
            foreach($allResearch as $key => $research) {
                $research->attLevel = 0;
                $research->defLevel = 0;
            }
        }
        if(count($planetaryResources)>0)
        {
            return view('simulation.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources,
                'planetaryStorage' => $planetaryResources,
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'allShips' => $allShips,
                'allResearch' => $allResearch,
                'allDefense' => $allDefense,
                'report' => $report,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function calc($planet_id)
    {
        $data = request()->validate([
            'sim.att.ship.1' => 'required|integer',
            'sim.att.ship.2' => 'required|integer',
            'sim.att.ship.3' => 'required|integer',
            'sim.att.ship.4' => 'required|integer',
            'sim.att.ship.5' => 'required|integer',
            'sim.att.ship.6' => 'required|integer',
            'sim.att.ship.7' => 'required|integer',
            'sim.att.ship.8' => 'required|integer',
            'sim.att.ship.9' => 'required|integer',
            'sim.att.ship.10' => 'required|integer',
            'sim.att.ship.11' => 'required|integer',
            'sim.att.ship.12' => 'required|integer',
            'sim.att.ship.13' => 'required|integer',
            'sim.att.ship.14' => 'required|integer',
            'sim.att.ship.15' => 'required|integer',
            'sim.att.ship.16' => 'required|integer',
            'sim.att.ship.17' => 'required|integer',
            'sim.att.ship.18' => 'required|integer',
            'sim.att.ship.19' => 'required|integer',
            'sim.def.ship.1' => 'required|integer',
            'sim.def.ship.2' => 'required|integer',
            'sim.def.ship.3' => 'required|integer',
            'sim.def.ship.4' => 'required|integer',
            'sim.def.ship.5' => 'required|integer',
            'sim.def.ship.6' => 'required|integer',
            'sim.def.ship.7' => 'required|integer',
            'sim.def.ship.8' => 'required|integer',
            'sim.def.ship.9' => 'required|integer',
            'sim.def.ship.10' => 'required|integer',
            'sim.def.ship.11' => 'required|integer',
            'sim.def.ship.12' => 'required|integer',
            'sim.def.ship.13' => 'required|integer',
            'sim.def.ship.14' => 'required|integer',
            'sim.def.ship.15' => 'required|integer',
            'sim.def.ship.16' => 'required|integer',
            'sim.def.ship.17' => 'required|integer',
            'sim.def.ship.18' => 'required|integer',
            'sim.def.ship.19' => 'required|integer',
            'sim.def.def.1' => 'required|integer',
            'sim.def.def.2' => 'required|integer',
            'sim.def.def.3' => 'required|integer',
            'sim.def.def.4' => 'required|integer',
            'sim.att.research.7' => 'required|integer',
            'sim.att.research.8' => 'required|integer',
            'sim.att.research.9' => 'required|integer',
            'sim.att.research.12' => 'required|integer',
            'sim.att.research.13' => 'required|integer',
            'sim.att.research.14' => 'required|integer',
            'sim.def.research.7' => 'required|integer',
            'sim.def.research.8' => 'required|integer',
            'sim.def.research.9' => 'required|integer',
            'sim.def.research.12' => 'required|integer',
            'sim.def.research.13' => 'required|integer',
            'sim.def.research.14' => 'required|integer',
        ]);
        $result = Controller::fightCalculation($data, true);

        $attacker = $result->attacker;
        $defender = $result->defender;
        return redirect('/simulation/' . $planet_id)->with('report', [$attacker, $defender]);
    }
}
