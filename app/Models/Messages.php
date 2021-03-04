<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAllUnreadMessages($receiver_id)
    {
        $postbox = Messages::where('receiver_id', $receiver_id)->where('read', 0)->orderBy('created_at', 'DESC')->get();
        foreach($postbox as $key => $message)
        {

            $message->read = 1;
            $message->save();
            if($message->user_id != 0)
            {
                $sender = User::where('id', $message->user_id)->first();
                $postbox[$key]->sender = $sender;
            }
        }
        return $postbox;
    }

    public static function getAllReadMessages($receiver_id)
    {
        $postbox = Messages::where('receiver_id', $receiver_id)->where('read', 1)->where('receiver_deleted', 0)->orderBy('created_at', 'DESC')->get();
        foreach($postbox as $key => $message)
        {
            if($message->user_id != 0)
            {
                $sender = User::where('id', $message->user_id)->first();
                $postbox[$key]->sender = $sender;
            }
        }
        return $postbox;
    }

    public static function getAllSendMessages($user_id)
    {
        $postbox = Messages::where('user_id', $user_id)->where('sender_deleted', 0)->get();
        foreach($postbox as $key => $message)
        {
            $receiver = User::where('id', $message->receiver_id)->first();
            $postbox[$key]->receiver = $receiver;
        }
        return $postbox;
    }

    public static function getUnreadMessagesById($receiver_id)
    {
        $postbox = Messages::where('receiver_id', $receiver_id)->where('read', 0)->first();
        return $postbox != null ? true : false;
    }
}
