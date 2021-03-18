<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Profile;
use App\Models\Planet;
use App\Models\Fleet;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'min:2', 'max:32', 'unique:users'],
            'race_id' => ['required'],
            'galaxy' => ['required', 'integer'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'isAdmin' => 0,
            'race_id' => $data['race_id'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if($user)
        {
            $startPlanet = Planet::colonizePlanetForStart($user['id'], $data['galaxy']);

            if($startPlanet)
            {
                Fleet::create([
                    'planet_id' => $startPlanet,
                    'ship_types' => '[{"ship_id":1,"ship_name":"Spionagesonde","amount":0},{"ship_id":2,"ship_name":"Warpsonde","amount":0},{"ship_id":3,"ship_name":"Delta Dancer","amount":1},{"ship_id":4,"ship_name":"Crusader","amount":0},{"ship_id":5,"ship_name":"Sternenj\u00e4ger","amount":0},{"ship_id":6,"ship_name":"Tarnbomber","amount":0},{"ship_id":7,"ship_name":"Kolonisationsschiff","amount":1},{"ship_id":8,"ship_name":"Kleines Handelsschiff","amount":0},{"ship_id":9,"ship_name":"Gro\u00dfes Handelsschiff","amount":0},{"ship_id":10,"ship_name":"Akira","amount":0},{"ship_id":11,"ship_name":"Cobra","amount":0},{"ship_id":12,"ship_name":"Pegasus","amount":0},{"ship_id":13,"ship_name":"Phoenix","amount":0},{"ship_id":14,"ship_name":"Aurora","amount":0},{"ship_id":15,"ship_name":"Lavi","amount":0},{"ship_id":16,"ship_name":"Moskito","amount":0},{"ship_id":17,"ship_name":"Vega","amount":0},{"ship_id":18,"ship_name":"Black Dragon","amount":0},{"ship_id":19,"ship_name":"Invasionseinheit","amount":0}]',
                ]);
                $profile = Profile::create([
                    'user_id' => $user['id'],
                    'start_planet' => $startPlanet,
                    'nickname' => $user['username'],
                ]);

                if($profile)
                {
                    return $user;
                }
            }

        }

    }
}
