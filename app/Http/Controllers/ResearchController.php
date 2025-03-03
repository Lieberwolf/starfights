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
        session(['default_planet' => $start_planet->start_planet]);
        return redirect('research/' . $start_planet->start_planet);
    }

    public function show($planet_id, $research_id = false)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $planetInformation = Planet::getOneById($planet_id);
        $allUserPlanets = Planet::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $researchList = Research::getAllAvailableResearches($user_id, $planet_id);
        $buildingList = Building::getAllAvailableBuildings($planet_id, $user_id);
        $currentResearch = Planet::getPlanetaryResearchProcess($planet_id, $user_id);
        $researchProcesses = Research::getResearchProcesses($allUserPlanets);
        $decreasers = [];

        $prevPlanet = false;
        foreach($allUserPlanets as $key => $planet)
        {
            if($planet->id == $planet_id)
            {
                if(!empty($allUserPlanets[$key-1]))
                {
                    $prevPlanet = $allUserPlanets[$key-1];
                } else {
                    $prevPlanet = $allUserPlanets[count($allUserPlanets)-1];
                }
            }
        }

        $nextPlanet = false;
        foreach($allUserPlanets as $key => $planet)
        {
            if($planet->id == $planet_id)
            {
                if(!empty($allUserPlanets[$key+1]))
                {
                    $nextPlanet = $allUserPlanets[$key+1];
                } else {
                    $nextPlanet = $allUserPlanets[0];
                }
            }
        }

        foreach($researchList as $key => $entry)
        {
            $researchList[$key]->inProgress = false;
            foreach($researchProcesses as $process)
            {
                if($process->research_id == $entry->research_id)
                {
                    $researchList[$key]->inProgress = true;
                }
            }

            $f1 = $entry->factor_1 > 0 ? $entry->factor_1 : 0.0001;
            $f2 = $entry->factor_2 > 0 ? $entry->factor_2 : 0.0001;
            $f3 = $entry->factor_3 > 0 ? $entry->factor_3 : 0.0001;

            $Grundzeit = $entry->initial_researchtime;
            $Stufe = $entry->level != null ? $entry->level : 1;
            $Modifikator1 = ($Stufe / ($f1) ) + $f2;
            $Modifikator2 = $Stufe * $f3;
            $suffix = ':';

            if($Stufe == 1)
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
            if($entry->level != null)
            {
                $f1 = $entry->fe_factor_1 > 0 ? $entry->fe_factor_1 : 0.0001;
                $f2 = $entry->fe_factor_2 > 0 ? $entry->fe_factor_2 : 0.0001;
                $f3 = $entry->fe_factor_3 > 0 ? $entry->fe_factor_3 : 0.0001;
                $cost = $entry->fe;
                $level = $entry ? $entry->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->fe =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->lut_factor_1 > 0 ? $entry->lut_factor_1 : 0.0001;
                $f2 = $entry->lut_factor_2 > 0 ? $entry->lut_factor_2 : 0.0001;
                $f3 = $entry->lut_factor_3 > 0 ? $entry->lut_factor_3 : 0.0001;
                $cost = $entry->lut;
                $level = $entry ? $entry->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->lut =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->cry_factor_1 > 0 ? $entry->cry_factor_1 : 0.0001;
                $f2 = $entry->cry_factor_2 > 0 ? $entry->cry_factor_2 : 0.0001;
                $f3 = $entry->cry_factor_3 > 0 ? $entry->cry_factor_3 : 0.0001;
                $cost = $entry->cry;
                $level = $entry ? $entry->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->cry =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->h2o_factor_1 > 0 ? $entry->h2o_factor_1 : 0.0001;
                $f2 = $entry->h2o_factor_2 > 0 ? $entry->h2o_factor_2 : 0.0001;
                $f3 = $entry->h2o_factor_3 > 0 ? $entry->h2o_factor_3 : 0.0001;
                $cost = $entry->h2o;
                $level = $entry ? $entry->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->h2o =  floor($cost * $Modifikator1 * $Modifikator2);

                $f1 = $entry->h2_factor_1 > 0 ? $entry->h2_factor_1 : 0.0001;
                $f2 = $entry->h2_factor_2 > 0 ? $entry->h2_factor_2 : 0.0001;
                $f3 = $entry->h2_factor_3 > 0 ? $entry->h2_factor_3 : 0.0001;
                $cost = $entry->h2;
                $level = $entry ? $entry->level : 0;
                $Modifikator1 = ($level / ($f1) ) + $f2;
                $Modifikator2 = $level * $f3;
                $researchList[$key]->h2 =  floor($cost * $Modifikator1 * $Modifikator2);
            }
        }

        foreach($buildingList as $building)
        {
            if($building->decrease_research_timeBy > 0 && $building->infrastructure != null)
            {
                $temp = new \stdClass();
                $temp->level = $building->infrastructure->level;
                $temp->factor = $building->decrease_research_timeBy;

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
            $selectedResearch = $researchList->firstWhere('research_id', $research_id);
            if($selectedResearch)
            {
                // check if selected building can be built (resources)
                if($planetaryResources['fe'] >= $selectedResearch->fe && $planetaryResources['lut'] >= $selectedResearch->lut && $planetaryResources['cry'] >= $selectedResearch->cry && $planetaryResources['h2o'] >= $selectedResearch->h2o && $planetaryResources['h2'] >= $selectedResearch->h2)
                {
                    $needle = $researchList->filter(function($value, $key) use ($research_id) {
                        if($value->research_id == $research_id)
                        {
                            return $value->buildable;
                        }
                    });
                    if(count($needle) > 0)
                    {
                        //start the build
                        $started = Research::startResearch($selectedResearch, $planet_id);
                        if($started)
                        {
                            //dd($selectedResearch);
                            // calculate new resources
                            $planetaryResources['fe'] -= $selectedResearch->fe;
                            $planetaryResources['lut'] -= $selectedResearch->lut;
                            $planetaryResources['cry'] -= $selectedResearch->cry;
                            $planetaryResources['h2o'] -= $selectedResearch->h2o;
                            $planetaryResources['h2'] -= $selectedResearch->h2;
                            Planet::setResourcesForPlanetById($planet_id, $planetaryResources);

                            return redirect('research/' . $planet_id);
                        }
                    } else {
                        return redirect('/research/' . $planet_id);
                    }

                } else {
                    return redirect('/research/' . $planet_id);
                }
            } else {
                // provide an error
                dd('error');
            }
        }

        $researchList = $researchList->filter(function($value, $key) {
            return $value->buildable == true;
        });

        if(count($planetaryResources)>0)
        {
            return view('research.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources,
                'planetaryStorage' => $planetaryResources,
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'planetInformation' => $planetInformation,
                'availableResearches' => $researchList,
                'currentResearch' => $currentResearch,
                'prevPlanet' => $prevPlanet,
                'nextPlanet' => $nextPlanet,
            ]);
        } else {
            return view('error.index');
        }
    }

    public function edit($planet_id)
    {
        $user_id = Auth::id();
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $researchList = Research::getAllAvailableResearches($user_id, $planet_id);
        $currentResearch = Planet::getPlanetaryResearchProcess($planet_id, $user_id);
        $selectedResearch = $researchList->firstWhere('research_id', $currentResearch->research_id);

        // calculate new resources
        // todo: higher levels => higher cost, it only calculates level 1 costs
        $planetaryResources['fe'] += $selectedResearch->fe;
        $planetaryResources['lut'] += $selectedResearch->lut;
        $planetaryResources['cry'] += $selectedResearch->cry;
        $planetaryResources['h2o'] += $selectedResearch->h2o;
        $planetaryResources['h2'] += $selectedResearch->h2;
        Planet::setResourcesForPlanetById($planet_id, $planetaryResources);

        $canceled = Research::cancelResearch($planet_id);
        if($canceled)
        {
            return redirect('research/' . $planet_id);
        } else {
            dd('something broke');
        }
    }
}
