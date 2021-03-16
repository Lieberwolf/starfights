<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Research;
use App\Models\Ship;
use App\Models\Turret;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use Illuminate\Support\Facades\DB;

class TechtreeController extends Controller
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
        return redirect('techtree/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        if(count($planetaryResources)>0)
        {
            return view('techtree.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function buildings($planet_id) {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allBuildings = Building::all();
        $allResearches = Research::all();
        $knowledge = Research::getUsersKnowledge($user_id);
        $infrastructure = DB::table('infrastructures')
                            ->where('planet_id', $planet_id)
                            ->get();

        foreach($allResearches as $key => $research) {
            foreach($knowledge as $tech) {
                if($tech->research_id == $research->id) {
                    $allResearches[$key]->level = $tech->level;
                }
            }
        }

        foreach($allBuildings as $key => $building) {
            foreach($infrastructure as $tech) {
                if($tech->building_id == $building->id) {
                    $allBuildings[$key]->level = $tech->level;
                }
            }
        }

        foreach($allBuildings as $key => $building) {
            $allBuildings[$key]->building_requirements = json_decode($building->building_requirements);
            $allBuildings[$key]->research_requirements = json_decode($building->research_requirements);
        }

        if(count($planetaryResources)>0)
        {
            return view('techtree.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'knowledge' => $allResearches,
                'infrastructure' => $allBuildings,
                'data' => $allBuildings,
                'isBuilding' => true,
                'isResearch' => false,
                'isShips' => false,
                'isDefense' => false,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function research($planet_id) {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allBuildings = Building::all();
        $allResearches = Research::all();
        $knowledge = Research::getUsersKnowledge($user_id);
        $infrastructure = DB::table('infrastructures')
                            ->where('planet_id', $planet_id)
                            ->get();

        foreach($allResearches as $key => $research) {
            foreach($knowledge as $tech) {
                if($tech->research_id == $research->id) {
                    $allResearches[$key]->level = $tech->level;
                }
            }
        }

        foreach($allBuildings as $key => $building) {
            foreach($infrastructure as $tech) {
                if($tech->building_id == $building->id) {
                    $allBuildings[$key]->level = $tech->level;
                }
            }
        }

        foreach($allResearches as $key => $research) {
            $allResearches[$key]->building_requirements = json_decode($research->building_requirements);
            $allResearches[$key]->research_requirements = json_decode($research->research_requirements);
        }

        if(count($planetaryResources)>0)
        {
            return view('techtree.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'knowledge' => $allResearches,
                'infrastructure' => $allBuildings,
                'data' => $allResearches,
                'isBuilding' => false,
                'isResearch' => true,
                'isShips' => false,
                'isDefense' => false,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function ships($planet_id) {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allShips = Ship::all();
        $allBuildings = Building::all();
        $allResearches = Research::all();
        $knowledge = Research::getUsersKnowledge($user_id);
        $infrastructure = DB::table('infrastructures')
                            ->where('planet_id', $planet_id)
                            ->get();

        foreach($allResearches as $key => $research) {
            foreach($knowledge as $tech) {
                if($tech->research_id == $research->id) {
                    $allResearches[$key]->level = $tech->level;
                }
            }
        }

        foreach($allBuildings as $key => $building) {
            foreach($infrastructure as $tech) {
                if($tech->building_id == $building->id) {
                    $allBuildings[$key]->level = $tech->level;
                }
            }
        }

        foreach($allShips as $key => $ship) {
            $allShips[$key]->building_requirements = json_decode($ship->building_requirements);
            $allShips[$key]->research_requirements = json_decode($ship->research_requirements);
        }

        if(count($planetaryResources)>0)
        {
            return view('techtree.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'knowledge' => $allResearches,
                'infrastructure' => $allBuildings,
                'data' => $allShips,
                'isBuilding' => false,
                'isResearch' => false,
                'isShips' => true,
                'isDefense' => false,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function turrets($planet_id) {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkBuildingProcesses($allUserPlanets);
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allTurrets = Turret::all();
        $allBuildings = Building::all();
        $allResearches = Research::all();
        $knowledge = Research::getUsersKnowledge($user_id);
        $infrastructure = DB::table('infrastructures')
                            ->where('planet_id', $planet_id)
                            ->get();

        foreach($allResearches as $key => $research) {
            foreach($knowledge as $tech) {
                if($tech->research_id == $research->id) {
                    $allResearches[$key]->level = $tech->level;
                }
            }
        }

        foreach($allBuildings as $key => $building) {
            foreach($infrastructure as $tech) {
                if($tech->building_id == $building->id) {
                    $allBuildings[$key]->level = $tech->level;
                }
            }
        }

        foreach($allTurrets as $key => $turret) {
            $allTurrets[$key]->building_requirements = json_decode($turret->building_requirements);
            $allTurrets[$key]->research_requirements = json_decode($turret->research_requirements);
        }

        if(count($planetaryResources)>0)
        {
            return view('techtree.detail', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'knowledge' => $allResearches,
                'infrastructure' => $allBuildings,
                'data' => $allTurrets,
                'isBuilding' => false,
                'isResearch' => false,
                'isShips' => false,
                'isDefense' => true,
            ]);
        } else {
            return view('error.index');
        }
    }
}
