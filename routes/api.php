<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ConstructionController;
use App\Models\Profile;
use \App\Models\Planet;

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
    Route::get('/getProfile/{user_id}', [Profile::class, 'getUsersProfileById']);
    Route::get('/getAllUserPlanets/{user_id}', [Planet::class, 'getAllUserPlanetsAsJSON']);
    Route::get('/getPlanetaryResourcesByPlanetId/{planet_id}/{user_id}', [Planet::class, 'getPlanetaryResourcesByPlanetId']);
    Route::get('/getOverview/{planet_id}', [OverviewController::class, 'show']);
});
