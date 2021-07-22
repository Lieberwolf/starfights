<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ConstructionController;
use App\Models\Profile;
use App\Models\Planet;
use App\Models\Building;
use \App\Http\Controllers\UniverseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'data'

], function ($router) {
    Route::get('/getProfile/{user_id}', [Profile::class, 'getUsersProfileByIdAsJSON']);
    Route::get('/getAllUserPlanets/{user_id}', [Planet::class, 'getAllUserPlanetsAsJSON']);
    Route::get('/getPlanetaryResourcesByPlanetId/{planet_id}/{user_id}', [Planet::class, 'getPlanetaryResourcesByPlanetIdAsJSON']);
    Route::get('/getOverview/{planet_id}', [OverviewController::class, 'show']);
    Route::get('/getAllAvailableBuildings/{planet_id}/{user_id}', [Building::class, 'getAllAvailableBuildingsAsJSON']);
    Route::get('/startConstruction/{planet_id}/{building_id}', [ConstructionController::class, 'showAsJSON']);
    Route::get('/getConstruction/{planet_id}', [Planet::class, 'getPlanetaryBuildingProcessAsJSON']);
    Route::get('/cancelConstruction/{planet_id}', [ConstructionController::class, 'editAsJSON']);
    Route::get('/universePart/{galaxy}/{system}', [UniverseController::class, 'showAsJSON']);
    Route::get('/getPlanetById/{planet_id}', [Planet::class, 'getOneByIdAsJSON']);

});
