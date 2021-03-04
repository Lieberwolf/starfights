<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile as Profile;
use App\Models\Planet as Planet;

class ResourcesController extends Controller
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
        return redirect('resources/' . $start_planet[0]->start_planet);
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $buildingsList = Building::getAllAvailableBuildings($planet_id, $user_id);

        $resourceBuildings = [];
        $rates = new \stdClass();
        $rates->fe = 0;
        $rates->lut = 0;
        $rates->cry = 0;
        $rates->h2o = 0;
        $rates->h2 = 0;

        $storage = new \stdClass();
        $storage->fe = 10000;
        $storage->lut = 10000;
        $storage->cry = 100;
        $storage->h2o = 10000;
        $storage->h2 = 1000;

        foreach($buildingsList as $building) {
            if($building->prod_fe > 0 || $building->prod_lut > 0 || $building->prod_cry > 0 || $building->prod_h2o || $building->prod_h2) {
                if($building->infrastructure != null && $building->infrastructure->level > 0) {

                    if($building->prod_fe > 0){
                        $factors = new \stdClass();
                        if($building->fe_factor_1 == null)
                        {
                            $factors->fe_factor_1 = 1.1000;
                            $factors->fe_factor_2 = 1.7500;
                            $factors->fe_factor_3 = 0.2300;
                        } else {
                            $factors->fe_factor_1 = $building->fe_factor_1;
                            $factors->fe_factor_2 = $building->fe_factor_2;
                            $factors->fe_factor_3 = $building->fe_factor_3;
                        }
                        $base = $building->prod_fe;
                        $lvl = $building->infrastructure ? $building->infrastructure->level : 1;
                        $Modifikator1 = ($lvl / $factors->fe_factor_1) + $factors->fe_factor_2;
                        $Modifikator2 = $lvl * $factors->fe_factor_3;
                        $rate =  $base * $Modifikator1 * $Modifikator2;

                        $building->rate_fe = $rate;
                    } else {
                        $building->rate_fe = 0;
                    }
                    $rates->fe += $building->rate_fe;
                    if($building->prod_lut > 0){
                        $factors = new \stdClass();
                        if($building->lut_factor_1 == null)
                        {
                            $factors->lut_factor_1 = 1.1000;
                            $factors->lut_factor_2 = 1.7500;
                            $factors->lut_factor_3 = 0.2300;
                        } else {
                            $factors->lut_factor_1 = $building->lut_factor_1;
                            $factors->lut_factor_2 = $building->lut_factor_2;
                            $factors->lut_factor_3 = $building->lut_factor_3;
                        }
                        $base = $building->prod_lut;
                        $lvl = $building->infrastructure ? $building->infrastructure->level : 1;
                        $Modifikator1 = ($lvl / $factors->lut_factor_1) + $factors->lut_factor_2;
                        $Modifikator2 = $lvl * $factors->lut_factor_3;
                        $rate =  $base * $Modifikator1 * $Modifikator2;

                        $building->rate_lut = $rate;
                    } else {
                        $building->rate_lut = 0;
                    }
                    $rates->lut += $building->rate_lut;
                    if($building->prod_cry > 0){
                        $factors = new \stdClass();
                        if($building->cry_factor_1 == null)
                        {
                            $factors->cry_factor_1 = 1.1000;
                            $factors->cry_factor_2 = 1.7500;
                            $factors->cry_factor_3 = 0.2300;
                        } else {
                            $factors->cry_factor_1 = $building->cry_factor_1;
                            $factors->cry_factor_2 = $building->cry_factor_2;
                            $factors->cry_factor_3 = $building->cry_factor_3;
                        }
                        $base = $building->prod_cry;
                        $lvl = $building->infrastructure ? $building->infrastructure->level : 1;
                        $Modifikator1 = ($lvl / $factors->cry_factor_1) + $factors->cry_factor_2;
                        $Modifikator2 = $lvl * $factors->cry_factor_3;
                        $rate =  $base * $Modifikator1 * $Modifikator2;

                        $building->rate_cry = $rate;
                    } else {
                        $building->rate_cry = 0;
                    }
                    $rates->cry += $building->rate_cry;
                    $rates->lut -= $building->cost_lut;
                    $rates->h2 -= $building->cost_h2;
                    if($building->prod_h2o > 0){
                        $factors = new \stdClass();
                        if($building->h2o_factor_1 == null)
                        {
                            $factors->h2o_factor_1 = 1.1000;
                            $factors->h2o_factor_2 = 1.7500;
                            $factors->h2o_factor_3 = 0.2300;
                        } else {
                            $factors->h2o_factor_1 = $building->h2o_factor_1;
                            $factors->h2o_factor_2 = $building->h2o_factor_2;
                            $factors->h2o_factor_3 = $building->h2o_factor_3;
                        }
                        $base = $building->prod_h2o;
                        $lvl = $building->infrastructure ? $building->infrastructure->level : 1;
                        $Modifikator1 = ($lvl / $factors->h2o_factor_1) + $factors->h2o_factor_2;
                        $Modifikator2 = $lvl * $factors->h2o_factor_3;
                        $rate =  $base * $Modifikator1 * $Modifikator2;

                        $building->rate_h2o = $rate;
                    } else {
                        $building->rate_h2o = 0;
                    }
                    $rates->h2o += $building->rate_h2o;
                    if($building->prod_h2 > 0){
                        $factors = new \stdClass();
                        if($building->h2_factor_1 == null)
                        {
                            $factors->h2_factor_1 = 1.1000;
                            $factors->h2_factor_2 = 1.7500;
                            $factors->h2_factor_3 = 0.2300;
                        } else {
                            $factors->h2_factor_1 = $building->h2_factor_1;
                            $factors->h2_factor_2 = $building->h2_factor_2;
                            $factors->h2_factor_3 = $building->h2_factor_3;
                        }

                        $base = $building->prod_h2;
                        $h2o_cost = $building->cost_h2o;
                        $lvl = $building->infrastructure ? $building->infrastructure->level : 1;
                        $Modifikator1 = ($lvl / $factors->h2_factor_1) + $factors->h2_factor_2;
                        $Modifikator2 = $lvl * $factors->h2_factor_3;
                        $rate =  $base * $Modifikator1 * $Modifikator2;
                        $h2o_cost = $h2o_cost * $Modifikator1 * $Modifikator2;

                        $building->rate_h2 = $rate;
                        $building->cost_h2o = $h2o_cost;
                    } else {
                        $building->rate_h2 = 0;
                        $building->cost_h2o = 0;
                    }
                    $rates->h2 += $building->rate_h2;
                    $rates->h2o -= $building->cost_h2o;
                    $resourceBuildings[] = $building;
                }
            }

            if($building->store_fe > 0) {
                if($building->infrastructure && $building->infrastructure->level > 0) {
                    $storage->fe += $building->store_fe * $building->infrastructure->level;
                }
            }
            if($building->store_lut > 0) {
                if($building->infrastructure && $building->infrastructure->level > 0) {
                    $storage->lut += $building->store_lut * $building->infrastructure->level;
                }
            }
            if($building->store_cry > 0) {
                if($building->infrastructure && $building->infrastructure->level > 0) {
                    $storage->cry += $building->store_cry * $building->infrastructure->level;
                }
            }
            if($building->store_h2o > 0) {
                if($building->infrastructure && $building->infrastructure->level > 0) {
                    $storage->h2o += $building->store_h2o * $building->infrastructure->level;
                }
            }
            if($building->store_h2 > 0) {
                if($building->infrastructure && $building->infrastructure->level > 0) {
                    $storage->h2 += $building->store_h2 * $building->infrastructure->level;
                }
            }
        }

        if(count($planetaryResources)>0)
        {
            return view('resources.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'resourceBuildings' => $resourceBuildings,
                'storage' => $storage,
                'rates' => $rates,
            ]);
        } else {
            return view('error.index');
        }
    }
}
