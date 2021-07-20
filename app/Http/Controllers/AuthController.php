<?php

namespace App\Http\Controllers;
use App\Models\Fleet;
use App\Models\Planet;
use App\Models\Profile;
use http\Env\Response;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);

    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'race_id' => 'required|integer',
            'galaxy' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'isAdmin' => 0,
            'race_id' => $request->race_id,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user)
        {
            $startPlanet = Planet::colonizePlanetForStart($user['id'], $request->galaxy);

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
                    return response()->json([
                        'message' => 'User successfully registered',
                        'user' => $user
                    ], 201);
                }
            } else {
                return response()->json([
                    'message' => 'Error at Startplanet',
                ], 500);
            }

        } else {
            return response()->json([
                'message' => 'Error at User creation',
            ], 500);
        }

    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600,
            'user' => auth()->user()
        ]);
    }

}
