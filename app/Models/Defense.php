<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Defense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getOneById($id)
    {
        return Defense::find($id);
    }

    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }

    public static function setDefenseForPlanet($planet_id, $turret_id, $amount)
    {
        $newDefense = array();
        $turrets = Turret::all();
        $planet = self::getOneById($planet_id);
        foreach($turrets as $key => $turret)
        {
            $defensePart = new \stdClass();
            $defensePart->turret_id = $turret->id;
            $defensePart->turret_name = $turret->turret_name;
            $defensePart->amount = 0;

            array_push($newDefense, $defensePart);
        }

        foreach($newDefense as $key => $turret)
        {
            if($turret->turret_id == $turret_id)
            {
                $turret->amount += $amount;
            }
        }

        $types = json_encode($newDefense);
        return Defense::create([
            'planet_id' => $planet_id,
            'turret_types' => $types
        ]);
    }

    public static function getTurretsAtPlanet($planet_id)
    {
        return Defense::where('planet_id', $planet_id)->first();
    }

    public static function setEmptyProcessForPlanet($planet_id)
    {
        return DB::table('turrets_process')->where('planet_id', $planet_id)->delete();
    }

}
