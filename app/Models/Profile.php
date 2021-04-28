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

    public function getAllianceForUser($user_id)
    {
        // add join to alliances table
        return DB::table('profiles as p')
                 ->leftJoin('alliances as a', 'a.id', '=', 'p.alliance_id')
                 ->where('p.user_id', $user_id)
                 ->first(['alliance_id', 'a.*']);
    }

    public function getAllianceByAllyid($ally_id)
    {
        // add join to alliances table
        return DB::table('alliances')
                 ->where('id', $ally_id)
                 ->first();
    }

    public static function foundAlliance($data, $user_id)
    {
        return DB::table('alliances')->insert([
            'alliance_name' => $data["name"],
            'alliance_tag' => $data["tag"],
            'founder_id' => $user_id,
            'created_at' => now()
        ]);
    }

    public static function setAlliance($user_id, $alliance_id)
    {
        return Profile::where('user_id', $user_id)->update(['alliance_id' => $alliance_id]);
    }

    public static function setAllianceToFounder($user_id)
    {
        $alliance = DB::table('alliances')->where('founder_id', $user_id)->first(['id']);
        $process = self::setAlliance($user_id, $alliance->id);
        return $process;
    }
}
