<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Research as Research;

class ResearchController extends Controller
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
        return redirect('research/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id, $research_id = false)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);
        Controller::checkResearchProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $planetInformation = Planet::getOneById($planet_id);

        $researchList = Research::getAllAvailableResearches($user_id, $planet_id);

        $currentResearch = Planet::getPlanetaryResearchProcess($planet_id, $user_id);

        $researchProcesses = Research::getResearchProcesses($allUserPlanets);

        foreach($researchList as $key => $entry)
        {
            $researchList[$key]->inProgress = false;
            foreach($researchProcesses as $process)
            {
                if($process->research_id == $entry->id)
                {
                    $researchList[$key]->inProgress = true;
                }
            }
        }

        // check is a build process started
        if($research_id)
        {
            // selected building exists?
            $selectedResearch = $researchList->firstWhere('id', $research_id);
            if($selectedResearch)
            {
                // check if selected building can be built (resources)

                if($planetaryResources[0][0]->fe >= $selectedResearch->fe && $planetaryResources[0][0]->lut >= $selectedResearch->lut && $planetaryResources[0][0]->cry >= $selectedResearch->cry && $planetaryResources[0][0]->h2o >= $selectedResearch->h2o && $planetaryResources[0][0]->h2 >= $selectedResearch->h2)
                {
                    // get requirements (research)
                    // get requirements (buildings)

                    //start the build
                    $started = Research::startResearch($selectedResearch, $planet_id);
                    if($started)
                    {
                        // calculate new resources
                        $planetaryResources[0][0]->fe -= $selectedResearch->fe;
                        $planetaryResources[0][0]->lut -= $selectedResearch->lut;
                        $planetaryResources[0][0]->cry -= $selectedResearch->cry;
                        $planetaryResources[0][0]->h2o -= $selectedResearch->h2o;
                        $planetaryResources[0][0]->h2 -= $selectedResearch->h2;
                        Planet::setResourcesForPlanetById($planet_id, $planetaryResources[0]);

                        return redirect('research/' . $planet_id);
                    }

                }
            } else {
                // provide an error
                dd('error');
            }
        }

        if(count($planetaryResources)>0)
        {
            return view('research.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'planetInformation' => $planetInformation,
                'availableResearches' => $researchList,
                'currentResearch' => $currentResearch,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function edit($planet_id)
    {
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $researchList = Research::getAllAvailableResearches($user_id, $planet_id);
        $currentResearch = Planet::getPlanetaryResearchProcess($planet_id, $user_id);
        $selectedResearch = $researchList->firstWhere('id', $currentResearch->research_id);

        // calculate new resources
        $planetaryResources[0][0]->fe += $selectedResearch->fe;
        $planetaryResources[0][0]->lut += $selectedResearch->lut;
        $planetaryResources[0][0]->cry += $selectedResearch->cry;
        $planetaryResources[0][0]->h2o += $selectedResearch->h2o;
        $planetaryResources[0][0]->h2 += $selectedResearch->h2;
        Planet::setResourcesForPlanetById($planet_id, $planetaryResources[0]);

        $canceled = Research::cancelResearch($planet_id);
        if($canceled)
        {
            return redirect('research/' . $planet_id);
        } else {
            dd('something broke');
        }
    }
}
