<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Messages as Messages;
use App\Models\Fleet as Fleet;
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

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        // first of all check processes
        Controller::checkBuildingProcesses($allUserPlanets);
        Controller::checkResearchProcesses($allUserPlanets);
        Controller::checkShipProcesses($allUserPlanets);
        Controller::checkFleetProcesses($allUserPlanets);
        //Controller::checkDefenseProcesses($planet_id);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $planetInformation = Planet::getOneById($planet_id);

        $planetaryBuildingProcesses = Planet::getAllPlanetaryBuildingProcess($allUserPlanets);
        $planetaryResearchProcesses = Planet::getAllPlanetaryResearchProcess($allUserPlanets, $user_id);

        $shipsAtPlanet = Fleet::getShipsAtPlanet($planet_id);
        $fleetsOnMission = Fleet::getFleetsOnMission($allUserPlanets);

        $knowledge = Research::getAllAvailableResearches($user_id, $planet_id);
        $maxPlanets = 10;

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

        $planetaryProcesses = array_values(Arr::sort($planetaryProcesses, function($value) {
            return $value->finished_at;
        }));

        $checkForNotifications = Messages::getUnreadMessagesById($user_id);

        $allPlanetPoints = Planet::getAllPlanetaryPointsByIds($allUserPlanets);
        $allResearchPoints = Research::getAllUserResearchPointsByUserId($user_id);

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

        if(count($planetaryResources)>0)
        {
            return view('overview.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'planetInformation' => $planetInformation,
                'planetaryBuildingProcesses' => $planetaryBuildingProcesses,
                'planetaryResearchProcesses' => $planetaryResearchProcesses,
                'fleetsOnMission' => $fleetsOnMission,
                'foreignFleets' => $incomingFleets,
                'planetaryProcesses' => $planetaryProcesses,
                'notifications' => $checkForNotifications,
                'allPlanetPoints' => $allPlanetPoints,
                'allResearchPoints' => $allResearchPoints,
                'shipsAtPlanet' => $shipsAtPlanet,
                'attackAlert' => $attackAlert,
                'maxPlanets' => $maxPlanets,
            ]);
        } else {
            return view('error.index');
        }

    }
}
