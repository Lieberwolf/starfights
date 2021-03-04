<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Research;

class BuildingDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $buildings = Building::all()->sortBy("id");
        $researches = Research::all()->sortBy("id");

        foreach($buildings as $key => $building)
        {
            $buildings[$key]['building_requirements'] = json_decode($building['building_requirements']);
            $buildings[$key]['research_requirements'] = json_decode($building['research_requirements']);
        }

        return view('building.dashboard.index', [
            "buildings" => $buildings,
            "researches" => $researches,
        ]);
    }

    public function create()
    {
        return view('building.dashboard.create');
    }

    public function show($id)
    {
        $building = Building::getOneById($id);

        return view('building.dashboard.show', [
            'building' => $building,
        ]);
    }

    public function edit($id)
    {
        $data = request()->validate([
            'building_name' => 'required',
            'description' => 'required',
            'fe' => 'required',
            'lut' => 'required',
            'cry' => 'required',
            'h2o' => 'required',
            'h2' => 'required',
            'prod_fe' => 'required',
            'prod_lut' => 'required',
            'prod_cry' => 'required',
            'prod_h2o' => 'required',
            'prod_h2' => 'required',
            'cost_fe' => 'required',
            'cost_lut' => 'required',
            'cost_cry' => 'required',
            'cost_h2o' => 'required',
            'cost_h2' => 'required',
            'store_fe' => 'required',
            'store_lut' => 'required',
            'store_cry' => 'required',
            'store_h2o' => 'required',
            'store_h2' => 'required',
            'allows_research' => '',
            'allows_ships' => '',
            'allows_defense' => '',
            'decrease_research_timeBy' => 'required',
            'decrease_ships_timeBy' => 'required',
            'decrease_defense_timeBy' => 'required',
            'decrease_building_timeBy' => 'required',
            'dynamic_buildtime' => 'required',
            'initial_buildtime' => 'required',
            'points' => 'required',
        ]);

        if(request()->has('allows_research')){
            //Checkbox checked
            $data['allows_research'] = 1;
        }else{
            //Checkbox not checked
            $data['allows_research'] = 0;
        }

        if(request()->has('allows_ships')){
            //Checkbox checked
            $data['allows_ships'] = 1;
        }else{
            //Checkbox not checked
            $data['allows_ships'] = 0;
        }

        if(request()->has('allows_defense')){
            //Checkbox checked
            $data['allows_defense'] = 1;
        }else{
            //Checkbox not checked
            $data['allows_defense'] = 0;
        }

        $building = Building::getOneById($id);
        $building->update($data);

        return redirect('/buildingdashboard/' . $id);
    }

    public function editF($id)
    {
        $data = request()->validate([
            "factor_1" => "numeric",
            "factor_2" => "numeric",
            "factor_3" => "numeric",
            "fe_factor_1" => "numeric",
            "fe_factor_2" => "numeric",
            "fe_factor_3" => "numeric",
            "lut_factor_1" => "numeric",
            "lut_factor_2" => "numeric",
            "lut_factor_3" => "numeric",
            "cry_factor_1" => "numeric",
            "cry_factor_2" => "numeric",
            "cry_factor_3" => "numeric",
            "h2o_factor_1" => "numeric",
            "h2o_factor_2" => "numeric",
            "h2o_factor_3" => "numeric",
            "h2_factor_1" => "numeric",
            "h2_factor_2" => "numeric",
            "h2_factor_3" => "numeric"
        ]);

        Building::updateOrCreateFactorsById($id, $data);

        return redirect('/buildingdashboard/' . $id);

    }

    public function store()
    {
        $data = request()->validate([
            'building_name' => 'required|unique:buildings',
            'description' => 'required',
            'fe' => 'required',
            'lut' => 'required',
            'cry' => 'required',
            'h2o' => 'required',
            'h2' => 'required',
            'prod_fe' => 'required',
            'prod_lut' => 'required',
            'prod_cry' => 'required',
            'prod_h2o' => 'required',
            'prod_h2' => 'required',
            'cost_fe' => 'required',
            'cost_lut' => 'required',
            'cost_cry' => 'required',
            'cost_h2o' => 'required',
            'cost_h2' => 'required',
            'store_fe' => 'required',
            'store_lut' => 'required',
            'store_cry' => 'required',
            'store_h2o' => 'required',
            'store_h2' => 'required',
            'allows_research' => '',
            'allows_ships' => '',
            'allows_defense' => '',
            'decrease_research_timeBy' => 'required',
            'decrease_ships_timeBy' => 'required',
            'decrease_defense_timeBy' => 'required',
            'decrease_building_timeBy' => 'required',
            'dynamic_buildtime' => 'required',
            'initial_buildtime' => 'required',
            'points' => 'required',
        ]);

        if(request()->has('allows_research')){
            //Checkbox checked
            $data['allows_research'] = 'on';
        }else{
            //Checkbox not checked
            $data['allows_research'] = 'off';
        }

        if(request()->has('allows_ships')){
            //Checkbox checked
            $data['allows_ships'] = 'on';
        }else{
            //Checkbox not checked
            $data['allows_ships'] = 'off';
        }

        if(request()->has('allows_defense')){
            //Checkbox checked
            $data['allows_defense'] = 'on';
        }else{
            //Checkbox not checked
            $data['allows_defense'] = 'off';
        }

        \App\Models\Building::create($data);

        return redirect('/buildingdashboard');

    }

    public function save()
    {

        $building_requirements = request()->input('building');

        // the $key is representing the buildings id
        foreach($building_requirements as $key => $building_requirement)
        {
            $json = json_encode($building_requirement["building_requirements"]);
            $buildingToUpdate = Building::getOneByName($key);
            $buildingToUpdate->building_requirements = $json;
            $buildingToUpdate->save();
        }

        return redirect('/buildingdashboard');
    }

    public function saveR()
    {

        $building_requirements = request()->input('building');

        // the $key is representing the researchs id
        foreach($building_requirements as $key => $research_requirement)
        {
            $json = json_encode($research_requirement["research_requirements"]);
            $buildingToUpdate = Building::getOneByName($key);
            $buildingToUpdate->research_requirements = $json;
            $buildingToUpdate->save();
        }

        return redirect('/buildingdashboard');
    }
}
