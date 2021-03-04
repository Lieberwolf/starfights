<?php

namespace App\Http\Controllers;

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
        $defenses = Defense::all()->sortBy("order");
        return view('defense.dashboard.index', [
            "defenses" => $defenses,
        ]);
    }

    public function create()
    {
        return view('defense.dashboard.create');
    }

    public function show($id)
    {
        $defense = Defense::getOneById($id);
        return view('defense.dashboard.show', [
            'defense' => $defense,
        ]);
    }

    public function edit($id)
    {
        $data = request()->validate([
            'order' => 'required|integer',
            'name' => 'required',
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

        if(request()->has('spy')){
            //Checkbox checked
            $data['spy'] = 'on';
        }else{
            //Checkbox not checked
            $data['spy'] = 'off';
        }

        if(request()->has('stealth')){
            //Checkbox checked
            $data['stealth'] = 'on';
        }else{
            //Checkbox not checked
            $data['stealth'] = 'off';
        }

        $defense = Defense::getOneById($id);
        $defense->update($data);

        return redirect('/defensedashboard');
    }

    public function store()
    {
        $data = request()->validate([
            'order' => 'required|integer',
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
}
