<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Models\Ship as Ship;

class Fleet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }

    public static function getOneById($planet_id)
    {
        return Fleet::find($planet_id);
    }

    public static function setFleetForPlanet($planet_id, $ship_id, $amount)
    {
        $newFleet = array();
        $ships = Ship::all();
        $planet = self::getOneById($planet_id);
        foreach ($ships as $key => $ship) {
            $fleetPart = new \stdClass();
            $fleetPart->ship_id = $ship->id;
            $fleetPart->ship_name = $ship->ship_name;
            $fleetPart->amount = 0;

            array_push($newFleet, $fleetPart);
        }

        foreach ($newFleet as $key => $fleet) {
            if ($fleet->ship_id == $ship_id) {
                $fleet->amount += $amount;
            }
        }

        $types = json_encode($newFleet);
        return Fleet::create([
            'planet_id' => $planet_id,
            'ship_types' => $types
        ]);
    }

    public static function getShipsAtPlanet($planet_id)
    {
        return Fleet::where([
            'mission' => null,
            'planet_id' => $planet_id
        ])->first();
    }

    public static function getFleetsOnMission($planet_ids)
    {

        $ids = [];
        foreach ($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        return DB::table('fleets AS f')
            ->leftJoin('planets AS p1', 'f.planet_id', '=', 'p1.id')
            ->leftJoin('planets AS p2', 'f.target', '=', 'p2.id')
            ->whereIn('f.planet_id', $ids)->whereNotNull('mission')
            ->get([
                'f.*',
                'p1.galaxy AS sourceGalaxy',
                'p1.system AS sourceSystem',
                'p1.planet AS sourcePlanet',
                'p2.galaxy AS targetGalaxy',
                'p2.system AS targetSystem',
                'p2.planet AS targetPlanet',
            ]);
    }

    public static function getShipsAtPlanetWithData($planet_id)
    {
        $fleet = Fleet::where('mission', null)->where('planet_id', $planet_id)->first();
        $shipList = Ship::all();
        $fleet->ship_types = json_decode($fleet->ship_types);

        foreach ($fleet->ship_types as $key => $ship) {
            if ($ship->amount > 0) {
                foreach ($shipList as $keyB => $item) {
                    if ($ship->ship_id == $item->id) {
                        $ship->baseData = $item;
                    }
                }
            }
        }

        return $fleet;
    }

    public static function setEmptyProcessForPlanet($planet_id)
    {
        return DB::table('ships_process')->where('planet_id', $planet_id)->delete();
    }

    public static function getFleetsOnMissionToPlayer($user_id, $allUserPlanets)
    {
        $planet_ids = [];

        foreach ($allUserPlanets as $planet) {
            $planet_ids[] = $planet->id;
        }

        return self::whereIn('target', $planet_ids)
            ->whereNotIn('planet_id', $planet_ids)
            ->leftJoin('planets AS sp', 'sp.id', '=', 'fleets.planet_id')
            ->leftJoin('planets AS tp', 'tp.id', '=', 'fleets.target')
            ->orderBy('fleets.arrival', 'ASC')
            ->get([
                'fleets.*',
                'sp.galaxy AS sourceGalaxy',
                'sp.system AS sourceSystem',
                'sp.planet AS sourcePlanet',
                'tp.galaxy AS targetGalaxy',
                'tp.system AS targetSystem',
                'tp.planet AS targetPlanet',
            ]);
    }

}
