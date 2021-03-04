<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planet as Planet;

class UniverseDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $planets = (new Planet)->universe();
        return view('universe.dashboard.index', [
            "planets" => $planets,
        ]);
    }

    public function create()
    {
        return view('universe.dashboard.create');
    }

    public function store()
    {
        $sizes = request()->validate([
            'galaxies' => 'required|integer',
            'systems' => 'required|integer',
            'planets' => 'required|integer',
        ]);

        if($sizes["galaxies"] != 0 && $sizes["systems"] != 0 && $sizes["planets"] != 0)
        {
            for($i = 1; $i <= $sizes["galaxies"]; $i++)
            {
                for($j = 1; $j <= $sizes["systems"]; $j++)
                {
                    for($k = 1; $k <= $sizes["planets"]; $k++)
                    {
                        $planet = [
                            'galaxy' => $i,
                            'system' => $j,
                            'planet' => $k,
                            'diameter' => 10000,
                            'temperature' => -10,
                            'atmosphere' => 0,
                            'resource_bonus' => 15,
                        ];

                        Planet::create($planet);
                    }
                }
            }
        } else {
            dd('something stupid happen');
        }

        return redirect('/universedashboard');

    }
}
