<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Profile as Profile;
use App\Models\Planet as Planet;
use App\Models\Messages as Messages;
use App\Models\User as User;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('messages/new/');
    }

    public function show($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);
        $user_id = Auth::id();
        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);
        $allUserPlanets = Controller::getAllUserPlanets($user_id);
        Controller::checkAllProcesses($allUserPlanets);
        $messages = Messages::getAllUnreadMessages($user_id);

        if(count($planetaryResources)>0)
        {
            return view('messages.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'messages' => $messages
            ]);
        } else {
            return view('error.index');
        }
    }

    public function inbox($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $messages = Messages::getAllReadMessages($user_id);

        if(count($planetaryResources)>0)
        {
            return view('messages.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'messages' => $messages
            ]);
        } else {
            return view('error.index');
        }
    }

    public function outbox($planet_id)
    {
        // update session with new planet id
        session(['default_planet' => $planet_id]);

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $messages = Messages::getAllSendMessages($user_id);

        if(count($planetaryResources)>0)
        {
            return view('messages.outbox', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'messages' => $messages
            ]);
        } else {
            return view('error.index');
        }
    }

    public function editInbox()
    {
        $user_id = Auth::id();
        $messages = Messages::getAllReadMessages($user_id);
        $request = request()->all();

        if(!array_key_exists('toBeDeleted', $request))
        {
            return redirect('messages/inbox');
        }

        foreach($request['toBeDeleted'] as $key => $index)
        {
            if($messages->find($key)->receiver_id == $user_id)
            {
                $message = $messages->find($key);
                $message->receiver_deleted = 1;
                unset($message->sender);
                $message->save();
            }
        }

        return redirect('messages/inbox');
    }

    public function editOutbox()
    {
        $user_id = Auth::id();
        $messages = Messages::getAllSendMessages($user_id);
        $request = request()->all();

        if(!array_key_exists('toBeDeleted', $request))
        {
            return redirect('messages/outbox');
        }

        foreach($request['toBeDeleted'] as $key => $index)
        {
            if($messages->find($key)->user_id == $user_id)
            {
                $message = $messages->find($key);
                $message->sender_deleted = 1;
                unset($message->receiver);
                $message->save();
            }
        }

        return redirect('messages/outbox');
    }

    public function send($receiver_id)
    {
        // update session with new planet id
        $planet_id = session('default_planet');

        $user_id = Auth::id();

        $allUserPlanets = Controller::getAllUserPlanets($user_id);

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

        $messages = Messages::getAllSendMessages($user_id);

        $receiver = User::where('id', $receiver_id)->first();

        if(count($planetaryResources)>0)
        {
            return view('messages.send', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0][0],
                'planetaryStorage' => $planetaryResources[1],
                'allUserPlanets' => $allUserPlanets,
                'activePlanet' => $planet_id,
                'messages' => $messages,
                'receiver' => $receiver
            ]);
        } else {
            return view('error.index');
        }
    }

    public function sending()
    {
        $data = request()->validate([
            'receiver_id' => 'required|integer',
            'message' => 'required|string',
            'subject' => ''
        ]);

        $data["user_id"] = Auth::id();

        $proof = Messages::create($data);

        if($proof)
        {

            // update session with new planet id
            $planet_id = session('default_planet');

            $user_id = Auth::id();

            $allUserPlanets = Controller::getAllUserPlanets($user_id);

            Controller::checkBuildingProcesses($allUserPlanets);

            $planetaryResources = Planet::getPlanetaryResourcesByPlanetId($planet_id, $user_id);

            if(count($planetaryResources)>0)
            {
                return view('messages.success', [
                    'defaultPlanet' => session('default_planet'),
                    'planetaryResources' => $planetaryResources[0][0],
                    'planetaryStorage' => $planetaryResources[1],
                    'allUserPlanets' => $allUserPlanets,
                    'activePlanet' => $planet_id,
                ]);
            } else {
                return view('error.index');
            }

        } else {
            return view('error.index');
        }
    }
}
