<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Race;

class RaceDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $races = Race::all()->sortBy("name");
        return view('race.dashboard.index', [
            "races" => $races,
        ]);
    }

    public function create()
    {
        return view('race.dashboard.create');
    }

    public function show($id)
    {
        $race = Race::getOneById($id);
        return view('race.dashboard.show', [
            'race' => $race,
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

        $race = Race::getOneById($id);
        $race->update($data);

        return redirect('/racedashboard');
    }

    public function store()
    {
        $data = request()->validate([
            'order' => 'required|integer',
            'name' => 'required|unique:races',
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

        \App\Models\Race::create($data);

        return redirect('/racedashboard');

    }
}
