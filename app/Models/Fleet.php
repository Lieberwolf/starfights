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
        foreach($ships as $key => $ship)
        {
            $fleetPart = new \stdClass();
            $fleetPart->ship_id = $ship->id;
            $fleetPart->ship_name = $ship->ship_name;
            $fleetPart->amount = 0;

            array_push($newFleet, $fleetPart);
        }

        foreach($newFleet as $key => $fleet)
        {
            if($fleet->ship_id == $ship_id)
            {
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

    public static function getFleetsOnMission($allUserPlanets)
    {

        $list = false;
        $query = '';
        foreach($allUserPlanets as $key => $planet) {
            $queryPart = Fleet::whereNotNull('mission')
                ->where('planet_id', $planet->id);
            if($key == 0) {
                $query = $queryPart;
            } else {
                $query->union($queryPart);
            }
        }

        $query = $query->get();
        //dd($query);

        if(count($query)>0) {
            foreach($query as $key => $fleet) {
                $fleet->readableSource = Planet::getOneById($fleet->planet_id);
                $fleet->readableTarget = Planet::getOneById($fleet->target);
                $list[] = $fleet;
            }
        }

        return $list;
    }

    public static function getShipsAtPlanetWithData($planet_id)
    {
        $fleet = Fleet::where('mission',null)->where('planet_id', $planet_id)->first();
        $shipList = Ship::all();
        $fleet->ship_types = json_decode($fleet->ship_types);

        foreach($fleet->ship_types as $key => $ship)
        {
            if($ship->amount > 0)
            {
                foreach($shipList as $keyB => $item)
                {
                    if($ship->ship_id == $item->id)
                    {
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

        foreach($allUserPlanets as $planet)
        {
            $planet_ids[] = $planet->id;
        }

        $list = [];
        foreach($allUserPlanets as $planet)
        {
            $temp = self::where('target', $planet->id)->whereNotIn('planet_id', $planet_ids)->leftJoin('planets', 'planets.id', '=', 'fleets.planet_id')->orderBy('fleets.arrival', 'ASC')->get();
            foreach($temp as $key => $tmp)
            {
                $temp[$key]->targetPlanet = Planet::getOneById($planet->id);
            }
            if(count($temp) > 0)
            {
                $list[] = $temp;
            }
        }

        return $list;
    }

}
