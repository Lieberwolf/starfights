<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function index($method, $param1 = false, $param2 = false)
    {
        switch($method)
        {
            /*
            Route::get('/api/v1/{method}', [App\Http\Controllers\ApiController::class, 'index']);
            Route::get('/api/v1/{method}/{param1}', [App\Http\Controllers\ApiController::class, 'index']);
            Route::get('/api/v1/{method}/{param1}/{param2}', [App\Http\Controllers\ApiController::class, 'index']);
            */

            case 'getPlayer':
                if($param1 != false)
                {
                    return DB::table('users')->where('id', $param1)->get(['race_id', 'username']);
                } else {
                    // required param not send
                    return [
                        'error' => 'getPlayer requires an user_id'
                    ];
                }
                break;

            case 'getPlayersPlanetList':
                if($param1 != false)
                {
                    return DB::table('planets')->where('user_id', $param1)->get([
                        'id',
                        'galaxy',
                        'system',
                        'planet',
                        'fe',
                        'lut',
                        'cry',
                        'h2o',
                        'h2',
                    ]);
                } else {
                    // required param not send
                    return [
                        'error' => 'getPlayersPlanetList requires an user_id'
                    ];
                }
                break;

            case 'getShipsAtPlanet':
                if($param1 != false)
                {
                    return DB::table('fleets')->where('planet_id', $param1)->where('mission', null)->get([
                        'ship_types'
                    ]);
                } else {
                    // required param not send
                    return [
                        'error' => 'getShipsAtPlanet requires a planet_id'
                    ];
                }
                break;

            case 'getBuildings':
                if($param1 != false && $param2 != false)
                {
                    $buildingListRaw = Building::getAllAvailableBuildings($param1, $param2);
                    $buildingList = Controller::factorizeBuildings($buildingListRaw);

                    return $buildingList;
                } else {
                    // required param not send
                    return [
                        'error' => 'getBuildings requires a planet_id and user_id'
                    ];
                }
                break;

            case 'getResearch':
                if($param1 != false)
                {
                    return DB::table('research')->where('id', $param1)->get();
                } else {
                    // required param not send
                    return [
                        'error' => 'getResearch requires a research_id'
                    ];
                }
                break;

            case 'getShip':
                if($param1 != false)
                {
                    return DB::table('ships')->where('id', $param1)->get();
                } else {
                    // required param not send
                    return [
                        'error' => 'getShip requires a ship_id'
                    ];
                }
                break;

            case 'getDefense':
                if($param1 != false)
                {
                    return DB::table('defenses')->where('id', $param1)->get();
                } else {
                    // required param not send
                    return [
                        'error' => 'getDefense requires a defense_id'
                    ];
                }
                break;

            case 'getUsersKnowledge':
                if($param1 != false)
                {
                    return DB::table('knowledge')->where('user_id', $param1)->get();
                } else {
                    // required param not send
                    return [
                        'error' => 'getUsersKnowledge requires an user_id'
                    ];
                }
                break;

            case 'getPlanetsInfrastructure':
                if($param1 != false)
                {
                    return DB::table('infrastructures')->where('planet_id', $param1)->get();
                } else {
                    // required param not send
                    return [
                        'error' => 'getPlanetsInfrastructure requires a planet_id'
                    ];
                }
                break;

            case 'getReport':
                if($param1 != false)
                {
                    return DB::table('reports')->where('link', $param1)->get();
                } else {
                    return [
                        'error' => 'getReport requires an id'
                    ];
                }
                break;
        }
    }
}
