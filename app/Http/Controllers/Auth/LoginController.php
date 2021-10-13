<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Planet;
use App\Models\Profile;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     */
    protected function authenticated(Request $request, $user)
    {
        // check if user is banned
        $banned = DB::table('banned')->where('banned.user_id', $user->id)->first();
        if($banned) {
            if($banned->banned_count > 3) {
                // permanently banned
                $this->guard()->logout();
                return redirect('/login')->with(['status' => 'Dieser Account ist permanent gesperrt!']);
            }
            if(strtotime($banned->banned_until) > now()->timestamp) {
                // temporarily banned
                $this->guard()->logout();
                return redirect('/login')->with(['status' => 'Dieser Account ist gesperrt! Bis: ' . $banned->banned_until]);
            } else {
                DB::table('banned')->where('banned.user_id', $user->id)->update([
                    'banned_until' => null
                ]);
            }
        }

        // essential users data
        $data = new \stdClass();
        $data->user_id = $user->id;
        $data->race_id = $user->race_id;
        $data->game_op = $user->game_op;
        session(['user' => $data]);

        // essential account/profile data
        $profile = Profile::getUsersProfileById($user->id);
        session(['profile' => $profile]);

        // essential planet data
        $allUserPlanets = Planet::getAllUserPlanets($user->id);
        session(['planets' => $allUserPlanets]);

        // check if user is on vacation
        $vacation = DB::table('vacation')->where('vacation.user_id', $user->id)->first();
        if($vacation) {
            if(strtotime($vacation->vacation_until) > now()->timestamp) {
                return redirect('/vacation/' . strtotime($vacation->vacation_until));
            }
        }
    }
}
