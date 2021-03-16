<?php

namespace App\Http\Controllers;

use App\Models\Defense;
use App\Models\Research;
use App\Models\Ship;
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
        $user_id = Auth::id();
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet[0]->start_planet]);
        return redirect('simulation/' . $start_planet[0]->start_planet);
    }

    public function show(Request $request, $planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allShips = Ship::all();
        $allResearch = Research::getAllResearchesWithEffect();
        $allDefense = Defense::all();
        $report = session('report');
        $request->session()->forget('report');

        if($report)
        {
            foreach($allShips as $key => $ship)
            {
                foreach($report[0]["ship"] as $attackerShip)
                {
                    if($ship->id == $attackerShip->id)
                    {
                        $allShips[$key]->attackerAmount = $attackerShip->amount;
                    }
                }
                foreach($report[1]["ship"] as $defenderShip)
                {
                    if($ship->id == $defenderShip->id)
                    {
                        $allShips[$key]->defenderAmount = $defenderShip->amount;
                    }
                }
            }
        }

        if(count($planetaryResources)>0)
        {
            return view('simulation.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
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
        $data = request();
        $attacker = $data["sim"]["att"];
        $defender = $data["sim"]["def"];
        $attacker["attack_value"] = 0;
        $attacker["final_attack_value"] = 0;
        $attacker["final_defense_value"] = 0;
        $attacker["defense_value"] = 0;
        $attacker["final_shield_value"] = 0;

        $defender["attack_value"] = 0;
        $defender["final_attack_value"] = 0;
        $defender["final_defense_value"] = 0;
        $defender["defense_value"] = 0;
        $defender["final_shield_value"] = 0;

        foreach($attacker["ship"] as $key => $amount)
        {
            // check if ship was selected
            if($amount > 0)
            {
                $attacker["ship"][$key] = new \stdClass();
                $attacker["ship"][$key] = Ship::getOneById($key);
                $attacker["ship"][$key]->amount = $amount;
                $attacker["attack_value"] += $attacker["ship"][$key]->amount * $attacker["ship"][$key]->attack;
                $attacker["defense_value"] += $attacker["ship"][$key]->amount * $attacker["ship"][$key]->defend;
            } else {
                unset($attacker["ship"][$key]);
            }
        }

        foreach($attacker["research"] as $key => $amount)
        {
            // check lvl of selected research
            if($amount > 0)
            {
                $attacker["research"][$key] = new \stdClass();
                $attacker["research"][$key] = Research::getOneById($key);
                $attacker["research"][$key]->level = $amount;
            }
            else {
                unset($attacker["research"][$key]);
            }
        }

        // calc final values for attack
        if(count($attacker["research"]) > 0)
        {
            foreach($attacker["research"] as $research)
            {
                if($research->increase_ship_attack > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += ($attacker["attack_value"] + $temp) * ($research->increase_ship_attack / 100);
                    }
                    $attacker["final_attack_value"] = $temp;
                }

                if($research->increase_ship_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += ($attacker["defense_value"] + $temp) * ($research->increase_ship_defense / 100);
                    }
                    $attacker["final_defense_value"] = $temp;
                }

                if($research->increase_shield_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += ($attacker["defense_value"] + $temp) * ($research->increase_shield_defense / 100);
                    }
                    $attacker["final_shield_value"] = $temp;
                }
            }
        }
        $attacker["final_attack_value"] += $attacker["attack_value"];
        $attacker["final_defense_value"] += $attacker["defense_value"] + $attacker["final_shield_value"];

        $attacker["final_attack_value"] = floor($attacker["final_attack_value"]);
        $attacker["final_defense_value"] = floor($attacker["final_defense_value"]);

        /////////// defender Part
        foreach($defender["ship"] as $key => $amount)
        {
            // check if ship was selected
            if($amount > 0)
            {
                $defender["ship"][$key] = new \stdClass();
                $defender["ship"][$key] = Ship::getOneById($key);
                $defender["ship"][$key]->amount = $amount;
                $defender["attack_value"] += $defender["ship"][$key]->amount * $defender["ship"][$key]->attack;
                $defender["defense_value"] += $defender["ship"][$key]->amount * $defender["ship"][$key]->defend;
            } else {
                unset($defender["ship"][$key]);
            }
        }

        foreach($defender["research"] as $key => $amount)
        {
            // check lvl of selected research
            if($amount > 0)
            {
                $defender["research"][$key] = new \stdClass();
                $defender["research"][$key] = Research::getOneById($key);
                $defender["research"][$key]->level = $amount;
            }
            else {
                unset($defender["research"][$key]);
            }
        }

        // calc final values for attack
        if(count($defender["research"]) > 0)
        {
            foreach($defender["research"] as $research)
            {
                if($research->increase_ship_attack > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += ($defender["attack_value"] + $temp) * ($research->increase_ship_attack / 100);
                    }
                    $defender["final_attack_value"] = $temp;
                }

                if($research->increase_ship_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += ($defender["defense_value"] + $temp) * ($research->increase_ship_defense / 100);
                    }
                    $defender["final_defense_value"] = $temp;
                }

                if($research->increase_shield_defense > 0)
                {
                    $temp = 0;
                    for($i = 0; $i < $research->level; $i++)
                    {
                        $temp += ($defender["defense_value"] + $temp) * ($research->increase_shield_defense / 100);
                    }
                    $defender["final_shield_value"] = $temp;
                }
            }
        }
        $defender["final_attack_value"] += $defender["attack_value"];
        $defender["final_defense_value"] += $defender["defense_value"] + $defender["final_shield_value"];

        $defender["final_attack_value"] = floor($defender["final_attack_value"]);
        $defender["final_defense_value"] = floor($defender["final_defense_value"]);

        // no ships? redirect this punk
        if(count($attacker["ship"]) <= 0 && count($defender["ship"]) <= 0)
        {
            return redirect('/simulation/' . $planet_id);
        }

        // formular:
        /*
         Angriff Flotte 1: Def flotte 2 - Att Flotte 1
         250000 - 120000 = 130000 RestDef Flotte 2
         Angriff Flotte 2: Def Flotte 1 - Att Flotte 2
         299500 - 110000 = 189500 RestDef Flotte 1
         */
        $survivedDef = $defender["final_defense_value"] - $attacker["final_attack_value"];
        if($defender["final_defense_value"] > 0 && $survivedDef > 0)
        {
            $survivedDefRatio = 100 / $defender["final_defense_value"] * $survivedDef;
        } else {
            $survivedDefRatio = 0;
        }
        $defender["survivedDefRatio"] = $survivedDefRatio;

        $survivedAtt = $attacker["final_defense_value"] - $defender["final_attack_value"];
        if($attacker["final_defense_value"] > 0 && $survivedAtt > 0)
        {
            $survivedAttRatio = 100 / $attacker["final_defense_value"] * $survivedAtt;
        } else {
            $survivedAttRatio = 0;
        }
        $attacker["survivedAttRatio"] = $survivedAttRatio;
        foreach($attacker["ship"] as $key => $attackerShip)
        {
            $attackerShip->newAmount = ceil($attackerShip->amount * ($survivedAttRatio/100));
        }

        foreach($defender["ship"] as $key => $defenderShip)
        {
            $defenderShip->newAmount = ceil($defenderShip->amount * ($survivedDefRatio/100));
        }

        return redirect('/simulation/' . $planet_id)->with('report', [$attacker, $defender]);
    }
}
