<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Messages as Messages;
use App\Models\Fleet as Fleet;
use App\Models\Defense as Defense;
use Illuminate\Support\Arr;

class OverviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::id();
        $start_planet = Profile::getStartPlanetByUserId($user_id);
        session(['default_planet' => $start_planet[0]->start_planet]);
        return redirect('overview/' . $start_planet[0]->start_planet);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetInformation = Planet::getOneById($planet_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $planetaryBuildingProcesses = Planet::getAllPlanetaryBuildingProcess($allUserPlanets);
        $planetaryResearchProcesses = Planet::getAllPlanetaryResearchProcess($allUserPlanets, $user_id);
        $shipsAtPlanet = Fleet::getShipsAtPlanet($planet_id);
        $turretsAtPlanet = Defense::getTurretsAtPlanet($planet_id);
        $fleetsOnMission = Fleet::getFleetsOnMission($allUserPlanets);
        $knowledge = Research::getAllAvailableResearches($user_id, $planet_id);
        $maxPlanets = 10;

        if($shipsAtPlanet)
        {
            $fleetCheck = false;
            foreach(json_decode($shipsAtPlanet->ship_types) as $ship)
            {
                if($ship->amount > 0)
                {
                    $fleetCheck = true;
                }
            }

            if(!$fleetCheck)
            {
                $shipsAtPlanet = false;
            }
        }

        if($turretsAtPlanet)
        {
            $turretCheck = false;
            foreach(json_decode($turretsAtPlanet->turret_types) as $turret)
            {
                if($turret->amount > 0)
                {
                    $turretCheck = true;
                }
            }

            if(!$turretCheck)
            {
                $turretsAtPlanet = false;
            }
        }

        foreach($knowledge as $research)
        {
            if($research->increase_max_planets != 0 && $research->knowledge != null)
            {
                $maxPlanets += $research->increase_max_planets * $research->knowledge->level;
            }
        }

        $planetaryProcesses = [];
        foreach($planetaryBuildingProcesses as $process)
        {
            if($process)
            {
                $planetaryProcesses[] = $process;
                $process->type = 'building';
            }
        }

        foreach($planetaryResearchProcesses as $process)
        {
            if($process)
            {
                $process->type = 'research';
                $planetaryProcesses[] = $process;
            }
        }
        if($fleetsOnMission)
        {
            foreach($fleetsOnMission[0] as $process)
            {
                    if($process)
                    {
                        $process->type = 'fleet';
                        if($process->mission == 0)
                        {
                            $process->finished_at = date("Y-m-d H:i:s", strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)));
                        } else {
                            $process->finished_at = $process->arrival;
                        }

                        $planetaryProcesses[] = $process;
                        // create extra return entry for return entry
                        if($process->mission != 0 && $process->mission != 1 && $process->mission != 3 && $process->mission != 5)
                        {
                            $processReturn = new \stdClass();
                            $processReturn->type = $process->type;
                            $processReturn->arrival = $process->arrival;
                            $processReturn->departure = $process->departure;
                            $processReturn->readableSource = $process->readableSource;
                            $processReturn->readableTarget = $process->readableTarget;
                            $processReturn->aborted = 1;
                            $processReturn->mission = $process->mission;
                            $processReturn->finished_at = date("Y-m-d H:i:s", strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)));
                            $planetaryProcesses[] = $processReturn;
                        }
                    }

            }
        }

        // incoming spy, scan, attack or invasion?
        $incomingFleets = Fleet::getFleetsOnMissionToPlayer($user_id, $allUserPlanets);
        $attackAlert = false;
        foreach($incomingFleets as $incoming_fleet)
        {
            foreach($incoming_fleet as $arriving_fleet)
            {
                // 3,4,6,7
                if($arriving_fleet->mission == 3 || $arriving_fleet->mission == 4 || $arriving_fleet->mission == 6 || $arriving_fleet->mission == 7)
                {
                    if(strtotime($arriving_fleet->arrival) < now()->timestamp)
                    {
                        $attackAlert = false;
                    } else {
                        $attackAlert = true;
                    }
                }
                $arriving_fleet->finished_at = date("Y-m-d H:i:s", strtotime($arriving_fleet->arrival));
                $arriving_fleet->type = 'foreignFleet';
                $planetaryProcesses[] = $arriving_fleet;

                // foreign fleet has arrived
                if(strtotime($arriving_fleet->arrival) < now()->timestamp)
                {
                    $fakeList = [];
                    $temp = new \stdClass();
                    $temp->id = $arriving_fleet->planet_id;
                    $fakeList[] = $temp;
                    Controller::checkFleetProcesses($fakeList);
                }
            }
        }

        $planetaryProcesses = array_values(Arr::sort($planetaryProcesses, function($value) {
            return strtotime($value->finished_at);
        }));

        $checkForNotifications = Messages::getUnreadMessagesById($user_id);

        $allPlanetPoints = Planet::getAllPlanetaryPointsByIds($allUserPlanets);
        $allResearchPoints = Research::getAllUserResearchPointsByUserId($user_id);

        $result = new \stdClass();
        $result->planet = new \stdClass();
        $result->planet->information = $planetInformation;
        $result->planet->processes = $planetaryProcesses;
        if($shipsAtPlanet){
            $result->planet->shipsAtPlanet = json_decode($shipsAtPlanet->ship_types);
        }else {
            $result->planet->shipsAtPlanet = null;
        }
        if($turretsAtPlanet){
            $result->planet->turretsAtPlanet = json_decode($turretsAtPlanet->turret_types);
        }else {
            $result->planet->turretsAtPlanet = null;
        }
        $result->planet->maxPlanets = $maxPlanets;
        $result->points = new \stdClass();
        $result->points->allPlanetPoints = $allPlanetPoints;
        $result->points->allResearchPoints = $allResearchPoints;

        return response()->json($result);

        if(count($planetaryResources)>0)
        {
            return view('overview.show', [
                'allUserPlanets' => $allUserPlanets,
                'planetaryBuildingProcesses' => $planetaryBuildingProcesses,
                'planetaryResearchProcesses' => $planetaryResearchProcesses,
                'planetaryProcesses' => $planetaryProcesses,
                'notifications' => $checkForNotifications,
                'attackAlert' => $attackAlert,
            ]);
        } else {
            return view('error.index');
        }

    }
}
