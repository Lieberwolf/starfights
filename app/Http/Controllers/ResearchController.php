<?php

namespace App\Http\Controllers;

use App\Models\Building;
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
        $buildingList = Building::getAllAvailableBuildings($planet_id, $user_id);
        $currentResearch = Planet::getPlanetaryResearchProcess($planet_id, $user_id);
        $researchProcesses = Research::getResearchProcesses($allUserPlanets);
        $decreasers = [];

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

            $f1 = $entry->factor_1 > 0 ? $entry->factor_1 : 0.0001;
            $f2 = $entry->factor_2 > 0 ? $entry->factor_2 : 0.0001;
            $f3 = $entry->factor_3 > 0 ? $entry->factor_3 : 0.0001;

            $Grundzeit = $entry->initial_researchtime;
            $Stufe = $entry->knowledge ? $entry->knowledge->level : 0;
            $Modifikator1 = ($Stufe / ($f1) ) + $f2;
            $Modifikator2 = $Stufe * $f3;
            $suffix = ':';

            if($Stufe == 0)
            {
                $researchList[$key]->actual_buildtime = $Grundzeit;
            } else {
                $researchList[$key]->actual_buildtime =  floor($Grundzeit * $Modifikator1 * $Modifikator2);
            }

            $days = floor(($researchList[$key]->actual_buildtime / (24*60*60)));
            $hours = ($researchList[$key]->actual_buildtime / (60*60)) % 24;
            $minutes = ($researchList[$key]->actual_buildtime / 60) % 60;
            $seconds = ($researchList[$key]->actual_buildtime / 1) % 60;

            if($days > 0)
            {
                $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
            } else {
                $days = '';
            }

            $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
            $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
            $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

            $researchList[$key]->readableBuildtime = $days . $hours . $minutes . $seconds;

            // calc resource cost
            if($entry->knowledge != null)
            {
                $f1 = $entry->fe_factor_1 > 0 ? $entry->fe_factor_1 : 0.0001;
                $f2 = $entry->fe_factor_2 > 0 ? $entry->fe_factor_2 : 0.0001;
                $f3 = $entry->fe_factor_3 > 0 ? $entry->fe_factor_3 : 0.0001;
                $cost = $entry->fe;
                $level = $entry->knowledge ? $entry->knowledge->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->fe =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->lut_factor_1 > 0 ? $entry->lut_factor_1 : 0.0001;
                $f2 = $entry->lut_factor_2 > 0 ? $entry->lut_factor_2 : 0.0001;
                $f3 = $entry->lut_factor_3 > 0 ? $entry->lut_factor_3 : 0.0001;
                $cost = $entry->lut;
                $level = $entry->knowledge ? $entry->knowledge->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->lut =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->cry_factor_1 > 0 ? $entry->cry_factor_1 : 0.0001;
                $f2 = $entry->cry_factor_2 > 0 ? $entry->cry_factor_2 : 0.0001;
                $f3 = $entry->cry_factor_3 > 0 ? $entry->cry_factor_3 : 0.0001;
                $cost = $entry->cry;
                $level = $entry->knowledge ? $entry->knowledge->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->cry =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->h2o_factor_1 > 0 ? $entry->h2o_factor_1 : 0.0001;
                $f2 = $entry->h2o_factor_2 > 0 ? $entry->h2o_factor_2 : 0.0001;
                $f3 = $entry->h2o_factor_3 > 0 ? $entry->h2o_factor_3 : 0.0001;
                $cost = $entry->h2o;
                $level = $entry->knowledge ? $entry->knowledge->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->h2o =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->h2_factor_1 > 0 ? $entry->h2_factor_1 : 0.0001;
                $f2 = $entry->h2_factor_2 > 0 ? $entry->h2_factor_2 : 0.0001;
                $f3 = $entry->h2_factor_3 > 0 ? $entry->h2_factor_3 : 0.0001;
                $cost = $entry->h2;
                $level = $entry->knowledge ? $entry->knowledge->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->h2 =  floor($cost * $Modifikator1 * $Modifikator2);
            }
        }

        foreach($buildingList as $building)
        {
            if($building->decrease_building_timeBy > 0 && $building->infrastructure != null)
            {
                $temp = new \stdClass();
                $temp->level = $building->infrastructure->level;
                $temp->factor = $building->decrease_building_timeBy;

                $decreasers[] = $temp;
            }
        }

        // apply researchtime bonusses
        foreach($researchList as $key => $research)
        {
            foreach($decreasers as $decreaser)
            {
                for($i = 0; $i < $decreaser->level; $i++)
                {
                    $research->actual_buildtime -= $research->actual_buildtime * ($decreaser->factor / 100);
                }
            }
            $days = floor(($researchList[$key]->actual_buildtime / (24*60*60)));
            $hours = ($researchList[$key]->actual_buildtime / (60*60)) % 24;
            $minutes = ($researchList[$key]->actual_buildtime / 60) % 60;
            $seconds = ($researchList[$key]->actual_buildtime / 1) % 60;

            if($days > 0)
            {
                $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
            } else {
                $days = '';
            }

            $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
            $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
            $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

            $researchList[$key]->readableBuildtime = $days . $hours . $minutes . $seconds;

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
