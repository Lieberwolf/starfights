<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research;
use App\Models\Building;

class ResearchDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $researches = Research::all()->sortBy("order");
        $buildings = Building::all()->sortBy("id");

        foreach($researches as $key => $research)
        {
            $researches[$key]['research_requirements'] = json_decode($research['research_requirements']);
            $researches[$key]['building_requirements'] = json_decode($research['building_requirements']);
        }

        return view('research.dashboard.index', [
            "researches" => $researches,
            "buildings" => $buildings,
        ]);
    }

    public function create()
    {
        return view('research.dashboard.create');
    }

    public function show($id)
    {
        $research = Research::getOneById($id);
        return view('research.dashboard.show', [
            'research' => $research,
        ]);
    }

    public function edit($id)
    {
        $data = request()->validate([
            'research_name' => 'required',
            'description' => '',
            'fe' => '',
            'lut' => '',
            'cry' => '',
            'h2o' => '',
            'h2' => '',
            'increase_spy' => '',
            'increase_counter_spy' => '',
            'increase_ship_attack' => '',
            'increase_ship_defense' => '',
            'increase_shield_defense' => '',
            'increase_rocket_drive' => '',
            'increase_turbine_drive' => '',
            'increase_warp_drive' => '',
            'increase_transwarp_drive' => '',
            'increase_ion_drive' => '',
            'increase_max_planets' => '',
            'increase_cargo' => '',
            'static_bonus' => '',
            'building_requirements' => '',
            'research_requirements' => '',
            'points' => '',
            'initial_researchtime' => ''
        ]);

        $research = Research::getOneById($id);
        $research->update($data);

        return redirect('/researchdashboard');
    }

    public function store()
    {
        $data = request()->validate([
            'order' => 'required|integer',
            'name' => 'required|unique:researchs',
        ]);

        \App\Models\Research::create($data);

        return redirect('/researchdashboard');

    }

    public function save()
    {

        $research_requirements = request()->input('research');

        // the $key is representing the researchs id
        foreach($research_requirements as $key => $research_requirement)
        {
            $json = json_encode($research_requirement["research_requirements"]);
            $researchToUpdate = Research::getOneByName($key);
            $researchToUpdate->research_requirements = $json;
            $researchToUpdate->save();
        }

        return redirect('/researchdashboard');
    }

    public function saveB()
    {
        $research_requirements = request()->input('research');

        // the $key is representing the researchs id
        foreach($research_requirements as $key => $building_requirement)
        {
            $json = json_encode($building_requirement["building_requirements"]);
            $researchToUpdate = Research::getOneByName($key);
            $researchToUpdate->building_requirements = $json;
            $researchToUpdate->save();
        }

        return redirect('/researchdashboard');
    }
}
