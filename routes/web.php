<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/**
 * Public
 */
// Overview
Route::get('/overview', [App\Http\Controllers\OverviewController::class, 'index']);
Route::get('/overview/{planet_id}', [App\Http\Controllers\OverviewController::class, 'show']);

// Planetary Overview
Route::get('/planetary', [App\Http\Controllers\PlanetaryOverviewController::class, 'index']);
Route::get('/planetary/{planet_id}', [App\Http\Controllers\PlanetaryOverviewController::class, 'show']);

// Construction
Route::get('/construction', [App\Http\Controllers\ConstructionController::class, 'index']);
Route::get('/construction/{planet_id}', [App\Http\Controllers\ConstructionController::class, 'show']);
Route::get('/construction/{planet_id}/edit', [App\Http\Controllers\ConstructionController::class, 'edit']);
Route::get('/construction/{planet_id}/{building_id}', [App\Http\Controllers\ConstructionController::class, 'show']);

// shipyard
Route::get('/shipyard', [App\Http\Controllers\ShipyardController::class, 'index']);
Route::get('/shipyard/{planet_id}', [App\Http\Controllers\ShipyardController::class, 'show']);
Route::post('/shipyard/{planet_id}/build', [App\Http\Controllers\ShipyardController::class, 'build']);
Route::get('/shipyard/{planet_id}/edit', [App\Http\Controllers\ShipyardController::class, 'edit']);

// defense
Route::get('/defense', [App\Http\Controllers\DefenseController::class, 'index']);
Route::get('/defense/{planet_id}', [App\Http\Controllers\DefenseController::class, 'show']);
Route::post('/defense/{planet_id}/build', [App\Http\Controllers\DefenseController::class, 'build']);
Route::get('/defense/{planet_id}/edit', [App\Http\Controllers\DefenseController::class, 'edit']);

// research
Route::get('/research', [App\Http\Controllers\ResearchController::class, 'index']);
Route::get('/research/{planet_id}', [App\Http\Controllers\ResearchController::class, 'show']);
Route::get('/research/{planet_id}/edit', [App\Http\Controllers\ResearchController::class, 'edit']);
Route::get('/research/{planet_id}/{research_id}', [App\Http\Controllers\ResearchController::class, 'show']);

// mission
Route::get('/mission', [App\Http\Controllers\MissionController::class, 'index']);
Route::get('/mission/{planet_id}', [App\Http\Controllers\MissionController::class, 'show']);
Route::post('/mission/{planet_id}/start', [App\Http\Controllers\MissionController::class, 'start']);
Route::post('/mission/{planet_id}/liftoff', [App\Http\Controllers\MissionController::class, 'liftoff']);
Route::get('/mission/{planet_id}/withdata/{targetGalaxy}/{targetSystem}/{targetPlanet}', [App\Http\Controllers\MissionController::class, 'withdata']);

// fleetlist
Route::get('/fleetlist', [App\Http\Controllers\FleetlistController::class, 'index']);
Route::get('/fleetlist/{planet_id}', [App\Http\Controllers\FleetlistController::class, 'show']);
Route::get('/fleetlist/{planet_id}/edit/{fleet_id}', [App\Http\Controllers\FleetlistController::class, 'edit']);

// resources
Route::get('/resources', [App\Http\Controllers\ResourcesController::class, 'index']);
Route::get('/resources/{planet_id}', [App\Http\Controllers\ResourcesController::class, 'show']);

// messages
Route::get('/messages', [App\Http\Controllers\MessagesController::class, 'index']);
Route::get('/messages/new', [App\Http\Controllers\MessagesController::class, 'show']);
Route::get('/messages/send/{receiver_id}', [App\Http\Controllers\MessagesController::class, 'send']);
Route::post('/messages/sending', [App\Http\Controllers\MessagesController::class, 'sending']);
Route::get('/messages/inbox', [App\Http\Controllers\MessagesController::class, 'inbox']);
Route::get('/messages/outbox', [App\Http\Controllers\MessagesController::class, 'outbox']);
Route::post('/messages/edit/inbox', [App\Http\Controllers\MessagesController::class, 'editInbox']);
Route::post('/messages/edit/outbox', [App\Http\Controllers\MessagesController::class, 'editOutbox']);

// universe
Route::get('/universe', [App\Http\Controllers\UniverseController::class, 'index']);
Route::get('/universe/{planet_id}/{galaxy}', [App\Http\Controllers\UniverseController::class, 'show']);
Route::get('/universe/{planet_id}/{galaxy}/{system}', [App\Http\Controllers\UniverseController::class, 'show']);

// search
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index']);
Route::get('/search/{planet_id}', [App\Http\Controllers\SearchController::class, 'show']);

// techtree
Route::get('/techtree', [App\Http\Controllers\TechtreeController::class, 'index']);
Route::get('/techtree/{planet_id}', [App\Http\Controllers\TechtreeController::class, 'show']);
Route::get('/techtree/{planet_id}/buildings', [App\Http\Controllers\TechtreeController::class, 'buildings']);
Route::get('/techtree/{planet_id}/research', [App\Http\Controllers\TechtreeController::class, 'research']);
Route::get('/techtree/{planet_id}/ships', [App\Http\Controllers\TechtreeController::class, 'ships']);
Route::get('/techtree/{planet_id}/turrets', [App\Http\Controllers\TechtreeController::class, 'turrets']);

// database
Route::get('/database', [App\Http\Controllers\DatabaseController::class, 'index']);
Route::get('/database/{planet_id}', [App\Http\Controllers\DatabaseController::class, 'show']);

// simulation
Route::get('/simulation', [App\Http\Controllers\SimulationController::class, 'index']);
Route::get('/simulation/{planet_id}', [App\Http\Controllers\SimulationController::class, 'show']);
Route::post('/simulation/{planet_id}/calc', [App\Http\Controllers\SimulationController::class, 'calc']);

// highscore
Route::get('/highscore', [App\Http\Controllers\HighscoreController::class, 'index']);
Route::get('/highscore/{planet_id}', [App\Http\Controllers\HighscoreController::class, 'show']);

// settings
Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index']);
Route::get('/settings/{planet_id}', [App\Http\Controllers\SettingsController::class, 'show']);

// alliance
Route::get('/alliance', [App\Http\Controllers\AllianceController::class, 'index']);
Route::get('/alliance/{planet_id}', [App\Http\Controllers\AllianceController::class, 'show']);

// profile
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index']);
Route::get('/profile/{user_id}', [App\Http\Controllers\ProfileController::class, 'show']);

// report
Route::get('/report', [App\Http\Controllers\ReportController::class, 'index']);
Route::get('/report/{report_uuid}', [App\Http\Controllers\ReportController::class, 'show']);

// details
Route::get('/details', [App\Http\Controllers\DetailsController::class, 'index']);
Route::get('/details/{planet_id}', [App\Http\Controllers\DetailsController::class, 'show']);
Route::post('/details/{planet_id}/name', [App\Http\Controllers\DetailsController::class, 'name']);
Route::post('/details/{planet_id}/image', [App\Http\Controllers\DetailsController::class, 'image']);

// notice
Route::get('/notice', [App\Http\Controllers\NoticeController::class, 'index']);
Route::get('/notice/{planet_id}', [App\Http\Controllers\NoticeController::class, 'show']);
Route::post('/notice/{planet_id}/edit', [App\Http\Controllers\NoticeController::class, 'edit']);

/**
 * Admin
 */

// Admin Universe Management done
Route::get('/universedashboard', [App\Http\Controllers\UniverseDashboardController::class, 'index'] );
Route::post('/universedashboard', [App\Http\Controllers\UniverseDashboardController::class, 'store'] );
Route::get('/universedashboard/create', [App\Http\Controllers\UniverseDashboardController::class, 'create'] );

// Admin Planet Management done
Route::get('/planetdashboard', [App\Http\Controllers\PlanetDashboardController::class, 'index'] );
Route::get('/planetdashboard/{id}', [App\Http\Controllers\PlanetDashboardController::class, 'show'] );
Route::post('/planetdashboard/{id}/edit', [App\Http\Controllers\PlanetDashboardController::class, 'edit'] );

// Admin Race Management
Route::get('/racedashboard', [App\Http\Controllers\RaceDashboardController::class, 'index']);
Route::post('/racedashboard', [App\Http\Controllers\RaceDashboardController::class, 'store']);
Route::get('/racedashboard/create', [App\Http\Controllers\RaceDashboardController::class, 'create']);
Route::get('/racedashboard/{id}', [App\Http\Controllers\RaceDashboardController::class, 'show']);
Route::post('/racedashboard/{id}/edit', [App\Http\Controllers\RaceDashboardController::class, 'edit']);


// Admin Building Management
Route::get('/buildingdashboard', [App\Http\Controllers\BuildingDashboardController::class, 'index']);
Route::post('/buildingdashboard', [App\Http\Controllers\BuildingDashboardController::class, 'store']);
Route::post('/buildingdashboard/save', [App\Http\Controllers\BuildingDashboardController::class, 'save']);
Route::post('/buildingdashboard/saveR', [App\Http\Controllers\BuildingDashboardController::class, 'saveR']);
Route::get('/buildingdashboard/create', [App\Http\Controllers\BuildingDashboardController::class, 'create']);
Route::get('/buildingdashboard/{id}', [App\Http\Controllers\BuildingDashboardController::class, 'show']);
Route::post('/buildingdashboard/{id}/edit', [App\Http\Controllers\BuildingDashboardController::class, 'edit']);
Route::post('/buildingdashboard/{id}/edit/factors', [App\Http\Controllers\BuildingDashboardController::class, 'editF']);

// Admin Research Management
Route::get('/researchdashboard', [App\Http\Controllers\ResearchDashboardController::class, 'index']);
Route::post('/researchdashboard', [App\Http\Controllers\ResearchDashboardController::class, 'store']);
Route::post('/researchdashboard/save', [App\Http\Controllers\ResearchDashboardController::class, 'save']);
Route::post('/researchdashboard/saveB', [App\Http\Controllers\ResearchDashboardController::class, 'saveB']);
Route::get('/researchdashboard/create', [App\Http\Controllers\ResearchDashboardController::class, 'create']);
Route::get('/researchdashboard/{id}', [App\Http\Controllers\ResearchDashboardController::class, 'show']);
Route::post('/researchdashboard/{id}/edit', [App\Http\Controllers\ResearchDashboardController::class, 'edit']);

// Admin Defense Management
Route::get('/defensedashboard', [App\Http\Controllers\DefenseDashboardController::class, 'index']);
Route::post('/defensedashboard', [App\Http\Controllers\DefenseDashboardController::class, 'store']);
Route::get('/defensedashboard/create', [App\Http\Controllers\DefenseDashboardController::class, 'create']);
Route::get('/defensedashboard/{id}', [App\Http\Controllers\DefenseDashboardController::class, 'show']);
Route::post('/defensedashboard/{id}/edit', [App\Http\Controllers\DefenseDashboardController::class, 'edit']);
Route::post('/defensedashboard/saveB', [App\Http\Controllers\DefenseDashboardController::class, 'saveB']);
Route::post('/defensedashboard/saveR', [App\Http\Controllers\DefenseDashboardController::class, 'saveR']);

// Admin Ship Management done
Route::get('/shipdashboard', [App\Http\Controllers\ShipDashboardController::class, 'index']);
Route::post('/shipdashboard', [App\Http\Controllers\ShipDashboardController::class, 'store']);
Route::post('/shipdashboard/saveB', [App\Http\Controllers\ShipDashboardController::class, 'saveB']);
Route::post('/shipdashboard/saveR', [App\Http\Controllers\ShipDashboardController::class, 'saveR']);
Route::get('/shipdashboard/create', [App\Http\Controllers\ShipDashboardController::class, 'create']);
Route::get('/shipdashboard/{id}', [App\Http\Controllers\ShipDashboardController::class, 'show']);
Route::post('/shipdashboard/{id}/edit', [App\Http\Controllers\ShipDashboardController::class, 'edit']);

/***
 * API
 */
Route::get('/api/v1/{method}', [App\Http\Controllers\ApiController::class, 'index']);
Route::get('/api/v1/{method}/{param1}', [App\Http\Controllers\ApiController::class, 'index']);
Route::get('/api/v1/{method}/{param1}/{param2}', [App\Http\Controllers\ApiController::class, 'index']);
