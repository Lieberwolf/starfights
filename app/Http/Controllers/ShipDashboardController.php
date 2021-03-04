<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research;
use App\Models\Building;
use App\Models\Ship as Ship;

class ShipDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $researches = Research::all()->sortBy("id");
        $buildings = Building::all()->sortBy("id");
        $ships = Ship::all()->sortBy("order");

        foreach($ships as $key => $ship)
        {
            $ships[$key]['research_requirements'] = json_decode($ship['research_requirements']);
            $ships[$key]['building_requirements'] = json_decode($ship['building_requirements']);
        }

        return view('ship.dashboard.index', [
            "ships" => $ships,
            "researches" => $researches,
            "buildings" => $buildings,
        ]);
    }

    public function create()
    {
        return view('ship.dashboard.create');
    }

    public function show($id)
    {
        $ship = Ship::getOneById($id);
        return view('ship.dashboard.show', [
            'ship' => $ship,
        ]);
    }

    public function edit($id)
    {
        $data = request()->validate([
            'order' => 'required|integer',
            'ship_name' => 'required',
            'description' => '',
            'speed' => 'required|integer',
            'attack' => 'required|integer',
            'defend' => 'required|integer',
            'cargo' => 'required|integer',
            'consumption' => 'required|integer',
            'spy' => '',
            'stealth' => '',
            'delta_scan' => '',
            'invasion' => '',
            'colonization' => '',
            'fe' => 'required|integer',
            'lut' => 'required|integer',
            'cry' => 'required|integer',
            'h2o' => 'required|integer',
            'h2' => 'required|integer',
            'initial_buildtime' => 'required|integer',
        ]);

        if(request()->has('spy')){
            //Checkbox checked
            $data['spy'] = 1;
        }else{
            //Checkbox not checked
            $data['spy'] = 0;
        }

        if(request()->has('stealth')){
            //Checkbox checked
            $data['stealth'] = 1;
        }else{
            //Checkbox not checked
            $data['stealth'] = 0;
        }

        if(request()->has('delta_scan')){
            //Checkbox checked
            $data['delta_scan'] = 1;
        }else{
            //Checkbox not checked
            $data['delta_scan'] = 0;
        }

        if(request()->has('invasion')){
            //Checkbox checked
            $data['invasion'] = 1;
        }else{
            //Checkbox not checked
            $data['invasion'] = 0;
        }

        if(request()->has('colonization')){
            //Checkbox checked
            $data['colonization'] = 1;
        }else{
            //Checkbox not checked
            $data['colonization'] = 0;
        }

        $ship = Ship::getOneById($id);
        $ship->update($data);

        return redirect('/shipdashboard');
    }

    public function store()
    {
        $data = request()->validate([
            'order' => 'required|integer',
            'name' => 'required|unique:ships',
            'description' => '',
            'speed' => 'required|integer',
            'attack' => 'required|integer',
            'defend' => 'required|integer',
            'cargo' => 'required|integer',
            'consumption' => 'required|integer',
            'spy' => '',
            'stealth' => '',
            'fe' => 'required|integer',
            'lut' => 'required|integer',
            'cry' => 'required|integer',
            'h2o' => 'required|integer',
            'h2' => 'required|integer',
        ]);

        if(request()->has('spy'))
        {
            //Checkbox checked
            $data['spy'] = 'on';
        }else{
            //Checkbox not checked
            $data['spy'] = 'off';
        }

        if(request()->has('stealth'))
        {
            //Checkbox checked
            $data['stealth'] = 'on';
        }else{
            //Checkbox not checked
            $data['stealth'] = 'off';
        }

        \App\Models\Ship::create($data);

        return redirect('/shipdashboard');
    }

    public function saveR()
    {

        $ship_requirements = request()->input('ship');

        // the $key is representing the researchs id
        foreach($ship_requirements as $key => $ship_requirement)
        {
            $json = json_encode($ship_requirement["research_requirements"]);
            $shipToUpdate = Ship::getOneByName($key);
            $shipToUpdate->research_requirements = $json;
            $shipToUpdate->save();
        }

        return redirect('/shipdashboard');
    }

    public function saveB()
    {
        $ship_requirements = request()->input('ship');

        // the $key is representing the building id
        foreach($ship_requirements as $key => $ship_requirement)
        {
            $json = json_encode($ship_requirement["building_requirements"]);
            $researchToUpdate = Ship::getOneByName($key);
            $researchToUpdate->building_requirements = $json;
            $researchToUpdate->save();
        }

        return redirect('/shipdashboard');
    }
}
