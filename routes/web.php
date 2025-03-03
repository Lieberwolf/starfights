<?php

use App\Http\Controllers\AllianceController;
use App\Http\Controllers\ConstructionController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DefenseController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\FleetlistController;
use App\Http\Controllers\HighscoreController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PlanetaryOverviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShipsOverviewController;
use App\Http\Controllers\ShipyardController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TechtreeController;
use App\Http\Controllers\UniverseController;
use App\Http\Controllers\VacationController;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\CheckVacation as CheckVacation;
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

Route::get('/chat/messages', [App\Http\Controllers\ChatController::class, 'index']);
Route::post('/chat/messages/send', [App\Http\Controllers\ChatController::class, 'send']);

/**
 * Public
 */

Route::middleware([CheckVacation::class])->group(function() {
    // Overview
    Route::get('/overview', [App\Http\Controllers\OverviewController::class, 'index']);
    Route::get('/overview/{planet_id}', [App\Http\Controllers\OverviewController::class, 'show']);

    // Planetary Overview
    Route::get('/planetary', [App\Http\Controllers\PlanetaryOverviewController::class, 'index']);
    Route::get('/planetary/{planet_id}', [App\Http\Controllers\PlanetaryOverviewController::class, 'show']);

    // Planetary Ships Overview
    Route::get('/ships', [App\Http\Controllers\ShipsOverviewController::class, 'index']);
    Route::get('/ships/{planet_id}', [App\Http\Controllers\ShipsOverviewController::class, 'show']);

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
    Route::get('/mission/start/{planet_id}', [App\Http\Controllers\MissionController::class, 'index']);
    Route::post('/mission/start/{planet_id}', [App\Http\Controllers\MissionController::class, 'start']);
    Route::post('/mission/liftoff/{planet_id}', [App\Http\Controllers\MissionController::class, 'liftoff']);
    Route::get('/mission/withdata/{targetGalaxy}/{targetSystem}/{targetPlanet}/{planet_id}', [App\Http\Controllers\MissionController::class, 'withdata']);
    Route::get('/mission/{planet_id}', [App\Http\Controllers\MissionController::class, 'show']);

    // fleetlist
    Route::get('/fleetlist', [App\Http\Controllers\FleetlistController::class, 'index']);
    Route::get('/fleetlist/{planet_id}', [App\Http\Controllers\FleetlistController::class, 'show']);
    Route::get('/fleetlist/{planet_id}/edit/{fleet_id}', [App\Http\Controllers\FleetlistController::class, 'edit']);

    // resources
    Route::get('/resources', [App\Http\Controllers\ResourcesController::class, 'index']);
    Route::get('/resources/{planet_id}', [App\Http\Controllers\ResourcesController::class, 'show']);

    // messages
    Route::get('/messages', [App\Http\Controllers\MessagesController::class, 'index']);
    Route::get('/messages/new/{planet_id}', [App\Http\Controllers\MessagesController::class, 'show']);
    Route::get('/messages/send/{receiver_id}', [App\Http\Controllers\MessagesController::class, 'send']);
    Route::post('/messages/sending', [App\Http\Controllers\MessagesController::class, 'sending']);
    Route::get('/messages/inbox/{planet_id}', [App\Http\Controllers\MessagesController::class, 'inbox']);
    Route::get('/messages/outbox/{planet_id}', [App\Http\Controllers\MessagesController::class, 'outbox']);
    Route::post('/messages/edit/inbox', [App\Http\Controllers\MessagesController::class, 'editInbox']);
    Route::post('/messages/edit/outbox', [App\Http\Controllers\MessagesController::class, 'editOutbox']);

    // universe
    Route::get('/universe', [App\Http\Controllers\UniverseController::class, 'index']);
    Route::get('/universe/{planet_id}/{galaxy}', [App\Http\Controllers\UniverseController::class, 'show']);
    Route::get('/universe/{planet_id}/{galaxy}/{system}', [App\Http\Controllers\UniverseController::class, 'show']);

    // search
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index']);
    Route::get('/search/{planet_id}', [App\Http\Controllers\SearchController::class, 'show']);
    Route::post('/search/{planet_id}/mode', [App\Http\Controllers\SearchController::class, 'mode']);

    // techtree
    Route::get('/techtree', [App\Http\Controllers\TechtreeController::class, 'index']);
    Route::get('/techtree/buildings/{planet_id}', [App\Http\Controllers\TechtreeController::class, 'buildings']);
    Route::get('/techtree/research/{planet_id}', [App\Http\Controllers\TechtreeController::class, 'research']);
    Route::get('/techtree/ships/{planet_id}', [App\Http\Controllers\TechtreeController::class, 'ships']);
    Route::get('/techtree/turrets/{planet_id}', [App\Http\Controllers\TechtreeController::class, 'turrets']);
    Route::get('/techtree/{planet_id}', [App\Http\Controllers\TechtreeController::class, 'show']);

    // database
    Route::get('/database', [App\Http\Controllers\DatabaseController::class, 'index']);
    Route::get('/database/buildings/{planet_id}', [App\Http\Controllers\DatabaseController::class, 'buildings']);
    Route::get('/database/research/{planet_id}', [App\Http\Controllers\DatabaseController::class, 'research']);
    Route::get('/database/ships/{planet_id}', [App\Http\Controllers\DatabaseController::class, 'ships']);
    Route::get('/database/turrets/{planet_id}', [App\Http\Controllers\DatabaseController::class, 'turrets']);
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
    Route::post('/settings/{planet_id}/updateN', [App\Http\Controllers\SettingsController::class, 'updateN']);
    Route::post('/settings/{planet_id}/updateE', [App\Http\Controllers\SettingsController::class, 'updateE']);
    Route::post('/settings/{planet_id}/updateP', [App\Http\Controllers\SettingsController::class, 'updateP']);
    Route::post('/settings/{planet_id}/delete', [App\Http\Controllers\SettingsController::class, 'delete']);

    // alliance
    Route::get('/alliance', [App\Http\Controllers\AllianceController::class, 'index']);
    Route::get('/alliance/{planet_id}', [App\Http\Controllers\AllianceController::class, 'redirect']);
    Route::get('/alliance/{planet_id}/found', [App\Http\Controllers\AllianceController::class, 'found']);
    Route::get('/alliance/{planet_id}/memberslist/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'memberslist']);
    Route::post('/alliance/{planet_id}/found', [App\Http\Controllers\AllianceController::class, 'founding']);
    Route::post('/alliance/{planet_id}/option', [App\Http\Controllers\AllianceController::class, 'option']);
    Route::post('/alliance/{planet_id}/send/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'send']);
    Route::post('/alliance/{planet_id}/apply/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'apply']);
    Route::get('/alliance/{planet_id}/delete/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'delete']);
    Route::get('/alliance/{planet_id}/leave/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'leave']);
    Route::post('/alliance/{planet_id}/logo/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'logo']);
    Route::post('/alliance/{planet_id}/description/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'description']);
    Route::get('/alliance/{planet_id}/logo/{alliance_id}/unset', [App\Http\Controllers\AllianceController::class, 'logoUnset']);
    Route::get('/alliance/{planet_id}/description/{alliance_id}/unset', [App\Http\Controllers\AllianceController::class, 'descriptionUnset']);
    Route::post('/alliance/{planet_id}/accept/{alliance_id}/{user_id}', [App\Http\Controllers\AllianceController::class, 'accept']);
    Route::post('/alliance/{planet_id}/decline/{alliance_id}/{user_id}', [App\Http\Controllers\AllianceController::class, 'decline']);
    Route::get('/alliance/{planet_id}/{alliance_id}', [App\Http\Controllers\AllianceController::class, 'show']);

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
    Route::post('/details/{planet_id}/delete', [App\Http\Controllers\DetailsController::class, 'delete']);
    Route::post('/details/{planet_id}/deleteImage', [App\Http\Controllers\DetailsController::class, 'deleteImage']);

    // notice
    Route::get('/notice', [App\Http\Controllers\NoticeController::class, 'index']);
    Route::get('/notice/{planet_id}', [App\Http\Controllers\NoticeController::class, 'show']);
    Route::post('/notice/{planet_id}/edit', [App\Http\Controllers\NoticeController::class, 'edit']);

    // statistics
    Route::get('/statistics', [App\Http\Controllers\StatisticsController::class, 'index']);
    Route::get('/statistics/{planet_id}/user/{users_id}', [App\Http\Controllers\StatisticsController::class, 'showUser']);
    Route::get('/statistics/{planet_id}/ally/{ally_id}', [App\Http\Controllers\StatisticsController::class, 'showAlly']);

});

// vacation pre view
Route::post('/vacation/enable', [App\Http\Controllers\VacationController::class, 'enable']);
Route::get('/vacation/deactivate', [App\Http\Controllers\VacationController::class, 'deactivate']);
Route::get('/vacation/{timestamp}', [App\Http\Controllers\VacationController::class, 'index']);
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

/***
 * iamthesenate
 */
Route::get('/senate', [App\Http\Controllers\SenateController::class, 'index']);
