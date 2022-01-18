<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $messagesToDelete = DB::table('chat')->get();

        if($messagesToDelete)
        {
            foreach($messagesToDelete as $message)
            {
                // chat messages older than one day? Delete them
                if(now()->timestamp - strtotime($message->created_at) > 86400)
                {
                    DB::table('chat')->where('id', $message->id)->delete();
                }
            }
        }

        return $messages = DB::table('chat as c')
            ->leftJoin('profiles as p', 'c.user_id', '=', 'p.user_id')
            ->get([
                 'c.*',
                 'p.nickname'
             ]);
    }

    public function send()
    {
        $data = request()->validate([
            'message' => 'required|string'
        ]);

        $user_id = Auth::id();

        DB::table('chat')->insert([
            'user_id' => $user_id,
            'message' => $data["message"],
            'created_at' => now(),
        ]);
    }
}
