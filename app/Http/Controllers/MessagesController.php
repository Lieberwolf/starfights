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
        $user = session()->get('user');$user_id = $user->user_id;
        $planetaryResources = Planet::getResourcesForPlanet($planet_id);
        $allUserPlanets = session()->get('planets');
        Controller::checkAllProcesses($allUserPlanets);
        $messages = Messages::getAllUnreadMessages($user_id);

        if(count($planetaryResources)>0)
        {
            return view('messages.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
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

        $user = session()->get('user');$user_id = $user->user_id;

        $allUserPlanets = session()->get('planets');

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getResourcesForPlanet($planet_id);

        $messages = Messages::getAllReadMessages($user_id);

        if(count($planetaryResources)>0)
        {
            return view('messages.show', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
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

        $user = session()->get('user');$user_id = $user->user_id;

        $allUserPlanets = session()->get('planets');

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getResourcesForPlanet($planet_id);

        $messages = Messages::getAllSendMessages($user_id);

        if(count($planetaryResources)>0)
        {
            return view('messages.outbox', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
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
        $user = session()->get('user');$user_id = $user->user_id;
        $messages = Messages::getAllReadMessages($user_id);
        $request = request()->all();

        if(!array_key_exists('toBeDeleted', $request))
        {
            return redirect('messages/inbox/' . session('default_planet'));
        }

        foreach($request['toBeDeleted'] as $key => $index)
        {
            if($messages->find($key)) {
                if($messages->find($key)->receiver_id == $user_id)
                {
                    $message = $messages->find($key);
                    $message->receiver_deleted = 1;
                    unset($message->sender);
                    $message->save();
                }
            }

        }

        return redirect('messages/inbox/' . session('default_planet'));
    }

    public function editOutbox()
    {
        $user = session()->get('user');$user_id = $user->user_id;
        $messages = Messages::getAllSendMessages($user_id);
        $request = request()->all();

        if(!array_key_exists('toBeDeleted', $request))
        {
            return redirect('messages/outbox/' . session('default_planet'));
        }

        foreach($request['toBeDeleted'] as $key => $index)
        {
            if($messages->find($key)) {
                if ($messages->find($key)->user_id == $user_id) {
                    $message = $messages->find($key);
                    $message->sender_deleted = 1;
                    unset($message->receiver);
                    $message->save();
                }
            }
        }

        return redirect('messages/outbox/' . session('default_planet'));
    }

    public function send($receiver_id)
    {
        // update session with new planet id
        $planet_id = session('default_planet');

        $user = session()->get('user');$user_id = $user->user_id;

        $allUserPlanets = session()->get('planets');

        Controller::checkBuildingProcesses($allUserPlanets);

        $planetaryResources = Planet::getResourcesForPlanet($planet_id);

        $messages = Messages::getAllSendMessages($user_id);

        $receiver = User::where('id', $receiver_id)->first();

        if(count($planetaryResources)>0)
        {
            return view('messages.send', [
                'defaultPlanet' => session('default_planet'),
                'planetaryResources' => $planetaryResources[0],
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

            $user = session()->get('user');$user_id = $user->user_id;

            $allUserPlanets = session()->get('planets');

            Controller::checkBuildingProcesses($allUserPlanets);

            $planetaryResources = Planet::getResourcesForPlanet($planet_id);

            if(count($planetaryResources)>0)
            {
                return view('messages.success', [
                    'defaultPlanet' => session('default_planet'),
                    'planetaryResources' => $planetaryResources[0],
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
