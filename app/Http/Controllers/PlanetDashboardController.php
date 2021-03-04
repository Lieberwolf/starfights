<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planet as Planet;

class PlanetDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $planets = (new Planet)->universe();
        return view('planet.dashboard.index', [
            "planets" => $planets,
        ]);
    }

    public function show($id)
    {
        $planet = Planet::getOneById($id);
        return view('planet.dashboard.show', [
            'planet' => $planet,
        ]);
    }

    public function edit($id)
    {
        $data = request()->validate([
            'galaxy' => 'required|integer',
            'system' => 'required|integer',
            'planet' => 'required|integer',
            'diameter' => 'required|integer',
            'temperature' => 'required|integer',
            'resource_bonus' => 'required|integer',
            'planet_name' => '',
            'user_id' => '',
            'fe' => 'numeric',
            'lut' => 'numeric',
            'cry' => 'numeric',
            'h2o' => 'numeric',
            'h2' => 'numeric',
            'rate_fe' => 'numeric',
            'rate_lut' => 'numeric',
            'rate_cry' => 'numeric',
            'rate_h2o' => 'numeric',
            'rate_h2' => 'numeric',
        ]);

        $planet = Planet::getOneById($id);
        $planet->update($data);

        return redirect('/planetdashboard');
    }
}
