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
        return DB::table('profiles')->where('user_id', $id)->first('start_planet');
    }

    public static function getUsersProfileById($user_id)
    {
        return Profile::where('profiles.user_id', $user_id)
            ->leftJoin('vacation as v', 'v.user_id', '=', 'profiles.user_id')
            ->first(['profiles.*', 'v.vacation', 'v.vacation_until', 'v.vacation_blocked_until']);
    }
}
