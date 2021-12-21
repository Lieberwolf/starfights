<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Fleet as Fleet;

class Planet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fleet()
    {
        return $this->hasMany(Fleet::class);
    }

    public function defense()
    {
        return $this->hasOne(Defense::class);
    }

    public static function getOneById($id)
    {
        return Planet::find($id);
    }

    public static function colonizePlanetForStart($user_id, $galaxy)
    {
        $freePlanets = Planet::all()->where('user_id', '=', null)->where('galaxy', '=', $galaxy);
        $min = 0;

        $list = [];
        foreach ($freePlanets as $planet) {
            $list[] = $planet->id;
        }

        $max = count($list);

        $index = rand($min, ($max - 1));

        foreach ($freePlanets as $free_planet) {
            if ($free_planet->id == $index) {
                $free_planet['user_id'] = $user_id;
                $free_planet['fe'] = 10000;
                $free_planet['lut'] = 10000;
                $free_planet['cry'] = 0;
                $free_planet['h2o'] = 10000;
                $free_planet['h2'] = 0;
                $free_planet['rate_fe'] = 10;
                $free_planet['rate_lut'] = 10;
                $free_planet['rate_h2o'] = 10;
                $free_planet->save();
                return $free_planet->id;
            }
        }
    }

    public function universe()
    {
        return DB::table('planets')->leftJoin('users AS u', 'u.id', '=', 'user_id')->select('u.id as user_id', 'u.username as username', 'planets.*')->get();
    }

    public function universePart($galaxy, $system)
    {
        return DB::table('planets')
            ->leftJoin('users AS u', 'u.id', '=', 'user_id')
            ->leftJoin('vacation as v', 'v.user_id', '=', 'u.id')
            ->leftJoin('profiles as p', 'p.user_id', '=', 'u.id')
            ->leftJoin('alliances as a', 'p.alliance_id', '=', 'a.id')
            ->select('u.id as user_id', 'u.username as username', 'planets.*', 'a.id as alliance_id', 'a.alliance_tag', 'v.vacation')
            ->where('planets.galaxy', '=', $galaxy)
            ->where('planets.system', '=', $system)
            ->orderBy('planets.planet')
            ->get();
    }

    public static function getAllUserPlanets($user_id)
    {
        return DB::table('planets')->where('user_id', $user_id)->get(['id', 'galaxy', 'system', 'planet']);
    }

    public static function getResourcesForAllPlanets($planet_ids)
    {
        $ids = [];
        foreach ($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }
        $lastStands = Planet::whereIn('id', $ids)->get();
        $buildingsList = Building::getAllBuildingsForPlanets($ids);
        foreach ($lastStands as $lastStand) {
            $buildings = [];
            foreach ($buildingsList as $key => $building) {
                if ($building->planet_id == $lastStand->id) {
                    $buildings[] = $building;
                    unset($buildingsList[$key]);
                }
            }

            $storage = new \stdClass();
            $storage['max_fe'] = 10000;
            $storage['max_lut'] = 10000;
            $storage['max_cry'] = 100;
            $storage['max_h2o'] = 10000;
            $storage['max_h2'] = 1000;

            foreach ($buildings as $building) {
                if ($building->store_fe > 0) {
                    if ($building->level > 0) {
                        $storage['max_fe'] += $building->store_fe * $building->level;
                    }
                }
                if ($building->store_lut > 0) {
                    if ($building->level > 0) {
                        $storage['max_lut'] += $building->store_lut * $building->level;
                    }
                }
                if ($building->store_cry > 0) {
                    if ($building->level > 0) {
                        $storage['max_cry'] += $building->store_cry * $building->level;
                    }
                }
                if ($building->store_h2o > 0) {
                    if ($building->level > 0) {
                        $storage['max_h2o'] += $building->store_h2o * $building->level;
                    }
                }
                if ($building->store_h2 > 0) {
                    if ($building->level > 0) {
                        $storage['max_h2'] += $building->store_h2 * $building->level;
                    }
                }
            }

            $ressFe = [
                "currentFe" => $lastStand->fe,
                "goneSeconds" => now()->diffInSeconds($lastStand->updated_at),
                "feRate" => $lastStand->rate_fe,
                "perSecond" => $lastStand->rate_fe / 3600,
                "tobeAdded" => ($lastStand->rate_fe / 3600) * now()->diffInSeconds($lastStand->updated_at),
                "newFe" => $lastStand->fe + ($lastStand->rate_fe / 3600) * now()->diffInSeconds($lastStand->updated_at),
            ];

            $ressLut = [
                "currentLut" => $lastStand->lut,
                "goneSeconds" => now()->diffInSeconds($lastStand->updated_at),
                "lutRate" => $lastStand->rate_lut,
                "perSecond" => $lastStand->rate_lut / 3600,
                "tobeAdded" => ($lastStand->rate_lut / 3600) * now()->diffInSeconds($lastStand->updated_at),
                "newLut" => $lastStand->lut + ($lastStand->rate_lut / 3600) * now()->diffInSeconds($lastStand->updated_at),
            ];

            $ressCry = [
                "currentCry" => $lastStand->cry,
                "goneSeconds" => now()->diffInSeconds($lastStand->updated_at),
                "cryRate" => $lastStand->rate_cry,
                "perSecond" => $lastStand->rate_cry / 3600,
                "tobeAdded" => ($lastStand->rate_cry / 3600) * now()->diffInSeconds($lastStand->updated_at),
                "newCry" => $lastStand->cry + ($lastStand->rate_cry / 3600) * now()->diffInSeconds($lastStand->updated_at),
            ];

            $ressH2o = [
                "currentH2o" => $lastStand->h2o,
                "goneSeconds" => now()->diffInSeconds($lastStand->updated_at),
                "h2oRate" => $lastStand->rate_h2o,
                "perSecond" => $lastStand->rate_h2o / 3600,
                "tobeAdded" => ($lastStand->rate_h2o / 3600) * now()->diffInSeconds($lastStand->updated_at),
                "newH2o" => $lastStand->h2o + ($lastStand->rate_h2o / 3600) * now()->diffInSeconds($lastStand->updated_at),
            ];

            $ressH2 = [
                "currentH2" => $lastStand->h2,
                "goneSeconds" => now()->diffInSeconds($lastStand->updated_at),
                "h2Rate" => $lastStand->rate_h2,
                "perSecond" => $lastStand->rate_h2 / 3600,
                "tobeAdded" => ($lastStand->rate_h2 / 3600) * now()->diffInSeconds($lastStand->updated_at),
                "newH2" => $lastStand->h2 + ($lastStand->rate_h2 / 3600) * now()->diffInSeconds($lastStand->updated_at),
            ];

            if ($ressFe["newFe"] <= $storage['max_fe']) {
                $lastStand->fe = $ressFe['newFe'];
            } else {
                $lastStand->fe = $storage['max_fe'];
            }
            if ($ressLut["newLut"] <= $storage['max_lut']) {
                $lastStand->lut = $ressLut['newLut'];
            } else {
                $lastStand->lut = $storage['max_lut'];
            }
            if ($ressCry["newCry"] <= $storage['max_cry']) {
                $lastStand->cry = $ressCry['newCry'];
            } else {
                $lastStand->cry = $storage['max_cry'];
            }
            if ($ressH2o["newH2o"] <= $storage['max_h2o']) {
                $lastStand->h2o = $ressH2o['newH2o'];
            } else {
                $lastStand->h2o = $storage['max_h2o'];
            }
            if ($ressH2["newH2"] <= $storage['max_h2']) {
                $lastStand->h2 = $ressH2['newH2'];
            } else {
                $lastStand->h2 = $storage['max_h2'];
            }

            if ($lastStand->fe < 0) {
                $lastStand->fe = 0;
            }
            if ($lastStand->lut < 0) {
                $lastStand->lut = 0;
            }
            if ($lastStand->cry < 0) {
                $lastStand->cry = 0;
            }
            if ($lastStand->h2o < 0) {
                $lastStand->h2o = 0;
            }
            if ($lastStand->h2 < 0) {
                $lastStand->h2 = 0;
            }
            $lastStand->save();
        }
    }

    public static function getResourcesForPlanet($planet_id)
    {

        $return = (array) tap(DB::table('planets')
            ->where('id', $planet_id))
            ->update([
                'fe' => DB::raw(
                    "
                    (
                        select *
                        from (
                            select if(
                                fe + rate_fe * timestampdiff(SECOND, updated_at, now())/3600 > max_fe,
                                max_fe,
                                fe + rate_fe * timestampdiff(SECOND, updated_at, now())/3600
                            )
                            from planets as p
                            where id = {$planet_id}
                        ) as fe
                    )
                "),

                'lut' => DB::raw(
                    "
                    (
                        select *
                        from (
                            select if(
                                lut + rate_lut * timestampdiff(SECOND, updated_at, now())/3600 > max_lut,
                                max_lut,
                                lut + rate_lut * timestampdiff(SECOND, updated_at, now())/3600
                            )
                            from planets as p
                            where id = {$planet_id}
                        ) as lut
                    )
                "),

                'cry' => DB::raw(
                    "
                    (
                        select *
                        from (
                            select if(
                                cry + rate_cry * timestampdiff(SECOND, updated_at, now())/3600 > max_cry,
                                max_cry,
                                cry + rate_cry * timestampdiff(SECOND, updated_at, now())/3600
                            )
                            from planets as p
                            where id = {$planet_id}
                        ) as cry
                    )
                "),

                'h2o' => DB::raw(
                    "
                    (
                        select *
                        from (
                            select if(
                                h2o + rate_h2o * timestampdiff(SECOND, updated_at, now())/3600 > max_h2o,
                                max_h2o,
                                h2o + rate_h2o * timestampdiff(SECOND, updated_at, now())/3600
                            )
                            from planets as p
                            where id = {$planet_id}
                        ) as h2o
                    )
                "),

                'h2' => DB::raw(
                    "
                    (
                        select *
                        from (
                            select if(
                                h2 + rate_h2 * timestampdiff(SECOND, updated_at, now())/3600 > max_h2,
                                max_h2,
                                h2 + rate_h2 * timestampdiff(SECOND, updated_at, now())/3600
                            )
                            from planets as p
                            where id = {$planet_id}
                        ) as h2
                    )
                "),

                'updated_at' => now(),

            ])
            ->first();

        return $return;
    }

    public static function setResourcesForPlanetById($planet_id, $resourceArray)
    {
        $planet = Planet::find($planet_id);
        $planet->fe = $resourceArray['fe'];
        $planet->lut = $resourceArray['lut'];
        $planet->cry = $resourceArray['cry'];
        $planet->h2o = $resourceArray['h2o'];
        $planet->h2 = $resourceArray['h2'];

        return $planet->save();
    }

    public static function getAllPlanetaryBuildingProcess($planet_ids)
    {
        $ids = [];
        foreach ($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        $list = DB::table('building_process AS bp')
            ->leftJoin('planets AS p', 'bp.planet_id', '=', 'p.id')
            ->leftJoin('buildings AS b', 'bp.building_id', '=', 'b.id')
            ->leftJoin('infrastructures AS i', function ($join) {
                $join->on('i.planet_id', '=', 'p.id');
                $join->on('i.building_id', '=', 'b.id');
            })
            ->whereIn('bp.planet_id', $ids)
            ->get();

        return $list;
    }

    public static function getAllPlanetaryResearchProcess($planet_ids, $user_id)
    {
        $ids = [];
        foreach ($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        $list = DB::table('research_process AS rp')
            ->leftJoin('planets AS p', 'rp.planet_id', '=', 'p.id')
            ->leftJoin('research AS r', 'rp.research_id', '=', 'r.id')
            ->leftJoin('knowledge AS k', function ($join) use ($user_id) {
                $join->on('k.user_id', '=', 'p.user_id');
                $join->on('k.research_id', '=', 'r.id');
            })
            ->whereIn('rp.planet_id', $ids)
            ->get();
        return $list;
    }

    public static function getAllPlanetaryShipsProcess($planet_ids)
    {
        $list = false;

        foreach ($planet_ids as $planet_id) {
            $list[] = DB::table('ships_process AS sp')
                ->leftJoin('planets AS p', 'sp.planet_id', '=', 'p.id')
                ->leftJoin('ships AS s', 'sp.ship_id', '=', 's.id')
                ->where('sp.planet_id', $planet_id->id)
                ->first();

        }
        return $list;
    }

    public static function getAllPlanetaryTurretsProcess($planet_ids)
    {
        $list = false;

        foreach ($planet_ids as $planet_id) {
            $list[] = DB::table('turrets_process AS tp')
                ->leftJoin('planets AS p', 'tp.planet_id', '=', 'p.id')
                ->leftJoin('turrets AS t', 'tp.turret_id', '=', 't.id')
                ->where('tp.planet_id', $planet_id->id)
                ->first();

        }
        return $list;
    }

    public static function getPlanetaryBuildingProcess($planet_id)
    {

        $building = DB::table('building_process AS bp')
            ->leftJoin('planets AS p', 'p.id', '=', 'bp.planet_id')
            ->leftJoin('buildings AS b', 'b.id', '=', 'bp.building_id')
            ->where('bp.planet_id', $planet_id)
            ->first();

        if ($building) {
            $structure = DB::table('infrastructures AS i')
                ->where('i.planet_id', '=', $planet_id)
                ->where('i.building_id', '=', $building->building_id)
                ->first();

            $building->infrastructure = $structure;
            return $building;
        } else {
            return null;
        }

    }

    public static function getPlanetaryResearchProcess($planet_id, $user_id)
    {

        return DB::table('research_process AS rp')
            ->leftJoin('planets AS p', 'p.id', '=', 'rp.planet_id')
            ->leftJoin('research AS r', 'r.id', '=', 'rp.research_id')
            ->leftJoin('knowledge AS k', function($join) use ($user_id) {
                $join->on('k.research_id', '=', 'r.id')->where('k.user_id', '=', $user_id);
            })
            ->where('rp.planet_id', $planet_id)->first();
    }

    public static function getPlanetaryShipProcess($planet_id)
    {
        return DB::table('ships_process as sp')
            ->where('sp.planet_id', $planet_id)
            ->leftJoin('ships as s', 's.id', '=', 'sp.ship_id')
            ->first();
    }

    public static function getPlanetaryTurretProcess($planet_id)
    {
        return DB::table('turrets_process as tp')
            ->where('tp.planet_id', $planet_id)
            ->leftJoin('turrets as t', 't.id', '=', 'tp.turret_id')
            ->first();
    }

    public static function getAllPlanetaryResourcesByIds($planet_ids)
    {
        $ids = [];
        foreach ($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        self::getResourcesForAllPlanets($ids, Auth::id());

    }

    public static function getPlanetaryPointsById($planet_id)
    {
        $points = 0;
        $buildings = DB::table('infrastructures AS i')
            ->where('i.planet_id', '=', $planet_id)
            ->leftJoin('buildings AS b', 'i.building_id', '=', 'b.id')
            ->get();

        if ($buildings) {
            foreach ($buildings as $key => $building) {
                $points += ($building->level * $building->points);
            }
        }
        return $points;
    }

    public static function getAllPlanetaryPointsByIds($planet_ids)
    {
        $ids = [];

        foreach($planet_ids as $planet_id) {
            $ids[] = $planet_id->id;
        }

        $points = 0;
        $buildings = DB::table('infrastructures AS i')
            ->whereIn('i.planet_id', $ids)
            ->leftJoin('buildings AS b', 'i.building_id', '=', 'b.id')
            ->get();

        if ($buildings) {
            foreach ($buildings as $key => $building) {
                $points += ($building->level * $building->points);
            }
        }

        return $points;
    }

    public static function cancelShipProcess($planet_id)
    {
        $process = DB::table('ships_process AS sp')
            ->where('sp.planet_id', $planet_id)
            ->leftJoin('ships AS s', 's.id', '=', 'sp.ship_id')
            ->leftJoin('planets AS p', 'p.id', '=', 'sp.planet_id')
            ->first([
                's.fe AS ship_fe',
                's.lut AS ship_lut',
                's.cry AS ship_cry',
                's.h2o AS ship_h2o',
                's.h2 AS ship_h2',
                'sp.*',
                'p.*'
            ]);

        $shipAmount = $process->amount_left;
        $resourceArray['fe'] = $process->fe + ($shipAmount * $process->ship_fe);
        $resourceArray['lut'] = $process->lut + ($shipAmount * $process->ship_lut);
        $resourceArray['cry'] = $process->cry + ($shipAmount * $process->ship_cry);
        $resourceArray['h2o'] = $process->h2o + ($shipAmount * $process->ship_h2o);
        $resourceArray['h2'] = $process->h2 + ($shipAmount * $process->ship_h2);

        $planet = new \stdClass();
        $planet->id = $planet_id;

        self::setResourcesForPlanetById($planet_id, $resourceArray);
        return Fleet::setEmptyProcessForPlanet($planet->id);
    }

    public static function cancelTurretProcess($planet_id)
    {
        $process = DB::table('turrets_process AS tp')
            ->where('tp.planet_id', $planet_id)
            ->leftJoin('turrets AS t', 't.id', '=', 'tp.turret_id')
            ->leftJoin('planets AS p', 'p.id', '=', 'tp.planet_id')
            ->first([
                't.fe AS turret_fe',
                't.lut AS turret_lut',
                't.cry AS turret_cry',
                't.h2o AS turret_h2o',
                't.h2 AS turret_h2',
                'tp.*',
                'p.*'
            ]);

        $shipAmount = $process->amount_left;
        $resourceArray['fe'] = $process->fe + ($shipAmount * $process->turret_fe);
        $resourceArray['lut'] = $process->lut + ($shipAmount * $process->turret_lut);
        $resourceArray['cry'] = $process->cry + ($shipAmount * $process->turret_cry);
        $resourceArray['h2o'] = $process->h2o + ($shipAmount * $process->turret_h2o);
        $resourceArray['h2'] = $process->h2 + ($shipAmount * $process->turret_h2);

        $planet = new \stdClass();
        $planet->id = $planet_id;

        self::setResourcesForPlanetById($planet_id, $resourceArray);
        return Turret::setEmptyProcessForPlanet($planet->id);
    }

    public static function getPlanetByCoordinates($galaxy, $system, $planet)
    {
        return DB::table('planets AS p')
            ->where('galaxy', $galaxy)
            ->where('system', $system)
            ->where('planet', $planet)
            ->leftJoin('users AS u', 'p.user_id', '=', 'u.id')
            ->leftJoin('vacation as v', 'v.user_id', '=', 'u.id')
            ->first([
                'p.*',
                'u.username',
                'v.vacation'
            ]);
    }

    public static function deletePlanet($planet_id)
    {
        DB::table('infrastructures')->where('planet_id', '=', $planet_id)->delete();
        DB::table('fleets')->where('planet_id', '=', $planet_id)->delete();
        DB::table('ships_process')->where('planet_id', '=', $planet_id)->delete();
        DB::table('turrets_process')->where('planet_id', '=', $planet_id)->delete();
        DB::table('building_process')->where('planet_id', '=', $planet_id)->delete();
        DB::table('research_process')->where('planet_id', '=', $planet_id)->delete();
        DB::table('defenses')->where('planet_id', '=', $planet_id)->delete();
        DB::table('planets')->where('id', '=', $planet_id)->update([
            'user_id' => null,
            'planet_name' => null,
            'image' => null,
            'fe' => null,
            'lut' => null,
            'cry' => null,
            'h2o' => null,
            'h2' => null,
            'rate_fe' => null,
            'rate_lut' => null,
            'rate_cry' => null,
            'rate_h2o' => null,
            'rate_h2' => null,

        ]);
    }

    public static function deletePlanetImage($planet_id)
    {
        DB::table('planets')->where('id', '=', $planet_id)->update([
            'image' => null,
        ]);
    }
}
