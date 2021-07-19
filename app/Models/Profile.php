<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getStartPlanetByUserId($id)
    {
        return DB::table('profiles')->where('user_id', $id)->get('start_planet');
    }

    public static function getUsersProfileById($user_id)
    {
        return Profile::where('user_id', $user_id)->first();
    }

    public static function getUsersProfileByIdAsJSON($user_id)
    {
        return Profile::where('user_id', $user_id)->first();
    }
}
