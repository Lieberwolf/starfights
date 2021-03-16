<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Research;
use App\Models\Turret;
use Illuminate\Http\Request;
use App\Models\Defense;

class DefenseDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $researches = Research::all()->sortBy("id");
        $buildings = Building::all()->sortBy("id");
        $defenses = Turret::all()->sortBy("order");


        foreach($defenses as $key => $defense)
        {
            $defenses[$key]['research_requirements'] = json_decode($defense['research_requirements']);
            $defenses[$key]['building_requirements'] = json_decode($defense['building_requirements']);
        }

        return view('defense.dashboard.index', [
            "defenses" => $defenses,
            "researches" => $researches,
            "buildings" => $buildings,
        ]);
    }

    public function create()
    {
        return view('defense.dashboard.create');
    }

    public function show($id)
    {
        $defense = Turret::getOneById($id);
        return view('defense.dashboard.show', [
            'defense' => $defense,
        ]);
    }

    public function edit($id)
    {
        $data = request()->validate([
            'turret_name' => 'required',
            'description' => '',
            'attack' => 'required|integer',
            'defend' => 'required|integer',
            'fe' => 'required|integer',
            'lut' => 'required|integer',
            'cry' => 'required|integer',
            'h2o' => 'required|integer',
            'h2' => 'required|integer',
        ]);

        $defense = Turret::getOneById($id);
        $defense->update($data);

        return redirect('/defensedashboard');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:defenses',
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

        \App\Models\Defense::create($data);

        return redirect('/defensedashboard');

    }

    public function saveR()
    {

        $turret_requirements = request()->input('turret');

        // the $key is representing the researchs id
        foreach($turret_requirements as $key => $turret_requirement)
        {
            $json = json_encode($turret_requirement["research_requirements"]);
            $turretToUpdate = Turret::getOneByName($key);
            $turretToUpdate->research_requirements = $json;
            $turretToUpdate->save();
        }

        return redirect('/defensedashboard');
    }

    public function saveB()
    {
        $turret_requirements = request()->input('turret');

        // the $key is representing the building id
        foreach($turret_requirements as $key => $turret_requirement)
        {
            $json = json_encode($turret_requirement["building_requirements"]);
            $researchToUpdate = Turret::getOneByName($key);
            $researchToUpdate->building_requirements = $json;
            $researchToUpdate->save();
        }

        return redirect('/defensedashboard');
    }
}
