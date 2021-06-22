<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function alliance()
    {
        $this->belongsTo(Alliances::class);
    }

    public static function addValues($attacker, $defender, $cargo)
    {
        // get attacker stat
        $attackerStat = Statistics::where('user_id', $attacker["home"]->user_id)->first();
        // no stats? Create it!
        if(!$attackerStat)
        {
            self::create([
                'user_id' => $attacker["home"]->user_id,
            ]);
            $attackerStat = Statistics::where('user_id', $attacker["home"]->user_id)->first();
        }
        // get attacker alliance stat (if)
        $attackerAlly = Profile::getUsersProfileById($attacker["home"]->user_id)->alliance_id;
        $attackerAllyStat = false;
        if($attackerAlly)
        {
            $attackerAllyStat = Statistics::where('alliance_id', $attackerAlly)->first();
            // no stats? Create it!
            if(!$attackerAllyStat)
            {
                self::create([
                    'alliance_id' => $attackerAlly,
                ]);
                $attackerAllyStat = Statistics::where('alliance_id', $attackerAlly)->first();
            }
        }

        // get defender stat
        $defenderStat = Statistics::where('user_id', $defender["home"]->user_id)->first();
        // no stats? Create it!
        if(!$defenderStat)
        {
            self::create([
                'user_id' => $defender["home"]->user_id,
            ]);
            $defenderStat = Statistics::where('user_id', $defender["home"]->user_id)->first();
        }
        // get defender alliance stat (if)
        $defenderAlly = Profile::getUsersProfileById($defender["home"]->user_id)->alliance_id;
        $defenderAllyStat = false;
        if($defenderAlly)
        {
            $defenderAllyStat = Statistics::where('alliance_id', $defenderAlly)->first();
            // no stats? Create it!
            if(!$defenderAllyStat)
            {
                self::create([
                    'alliance_id' => $defenderAlly,
                ]);
                $defenderAllyStat = Statistics::where('alliance_id', $defenderAlly)->first();
            }
        }

        $attackerShipStats = [];
        $defenderShipStats = [];
        if($defender["ship"] != null)
        {
            foreach($attacker["ship"] as $shipAtt)
            {
                foreach($defender["ship"] as $shipDef)
                {
                    if($shipDef->ship_id == $shipAtt->ship_id)
                    {
                        // step 1 fill attackers raw ship stat
                        $tempAtt = new \stdClass();
                        $tempAtt->ship_id = $shipAtt->ship_id;
                        $tempAtt->ship_name = $shipAtt->ship_name;
                        $tempAtt->lost = $shipAtt->amount - $shipAtt->newAmount;
                        $tempAtt->destroyed = $shipDef->amount - $shipDef->newAmount;
                        array_push($attackerShipStats, $tempAtt);

                        // step 2 fill defenders raw ship stat
                        $tempDef = new \stdClass();
                        $tempDef->ship_id = $shipDef->ship_id;
                        $tempDef->ship_name = $shipDef->ship_name;
                        $tempDef->lost = $tempAtt->destroyed;
                        $tempDef->destroyed = $tempAtt->lost;
                        array_push($defenderShipStats, $tempDef);
                    }
                }
            }
        } else {
            $attackerShipStats = null;
            $defenderShipStats = null;
        }


        $attackerTurretStats = [];
        $defenderTurretStats = [];
        if(gettype($defender) == "array")
        {
            if(array_key_exists("turrets", $defender))
            {
                foreach($defender["turrets"] as $turretDef)
                {
                    // step 1 fill attackers raw ship stat
                    $tempAtt = new \stdClass();
                    $tempAtt->turret_id = $turretDef->turret_id;
                    $tempAtt->turret_name = $turretDef->turret_name;
                    $tempAtt->lost = 0;
                    $tempAtt->destroyed = $turretDef->amount - $turretDef->newAmount;
                    array_push($attackerTurretStats, $tempAtt);

                    // step 2 fill defenders raw ship stat
                    $tempDef = new \stdClass();
                    $tempDef->turret_id = $turretDef->turret_id;
                    $tempDef->turret_name = $turretDef->turret_name;
                    $tempDef->lost = $tempAtt->destroyed;
                    $tempDef->destroyed = $tempAtt->lost;
                    array_push($defenderTurretStats, $tempDef);
                }
            } else {
                $attackerTurretStats = null;
                $defenderTurretStats = null;
            }
        } else {
            if($defender["turrets"] != null)
            {
                foreach($defender["turrets"] as $turretDef)
                {
                    // step 1 fill attackers raw ship stat
                    $tempAtt = new \stdClass();
                    $tempAtt->turret_id = $turretDef->turret_id;
                    $tempAtt->turret_name = $turretDef->turret_name;
                    $tempAtt->lost = 0;
                    $tempAtt->destroyed = $turretDef->amount - $turretDef->newAmount;
                    array_push($attackerTurretStats, $tempAtt);

                    // step 2 fill defenders raw ship stat
                    $tempDef = new \stdClass();
                    $tempDef->turret_id = $turretDef->turret_id;
                    $tempDef->turret_name = $turretDef->turret_name;
                    $tempDef->lost = $tempAtt->destroyed;
                    $tempDef->destroyed = $tempAtt->lost;
                    array_push($defenderTurretStats, $tempDef);
                }
            } else {
                $attackerTurretStats = null;
                $defenderTurretStats = null;
            }
        }


        $attackerResourceStats = [];
        $defenderResourceStats = [];
        if($cargo && $cargo != "null")
        {
            foreach(json_decode($cargo) as $res => $amount)
            {
                // step 1 fill attackers raw ship stat
                $tempAtt = new \stdClass();
                $tempAtt->res_id = $res;
                $tempAtt->lost = 0;
                $tempAtt->caught = $amount;
                array_push($attackerResourceStats, $tempAtt);

                // step 2 fill defenders raw ship stat
                $tempDef = new \stdClass();
                $tempDef->res_id = $res;
                $tempDef->lost = $tempAtt->caught;
                $tempDef->caught = $tempAtt->lost;
                array_push($defenderResourceStats, $tempDef);
            }
        } else {
            $attackerResourceStats = null;
            $defenderResourceStats = null;
        }

        // update attacker stats
        if($attackerStat->ship_types == null)
        {
            // first ever entry
            if($attackerShipStats)
            {
                $attackerStat->ship_types = json_encode($attackerShipStats);
            } else {
                $attackerStat->ship_types = null;
            }
        }
        else {
            if($attackerShipStats)
            {
                // run through existing stats
                $oldShipsValues = json_decode($attackerStat->ship_types);
                if($oldShipsValues)
                {
                    foreach($oldShipsValues as $oldShipValues)
                    {
                        // run through new values
                        foreach($attackerShipStats as $newShipValues)
                        {
                            // compare ids
                            if($oldShipValues->ship_id == $newShipValues->ship_id)
                            {
                                $oldShipValues->lost += $newShipValues->lost;
                                $oldShipValues->destroyed += $newShipValues->destroyed;
                            }
                        }

                    }
                    // save updated stats
                    $attackerStat->ship_types = json_encode($oldShipsValues);
                }

            }

        }
        if($attackerStat->turret_types == null)
        {
            // first ever entry
            if($attackerTurretStats)
            {
                $attackerStat->turret_types = json_encode($attackerTurretStats);
            } else {
                $attackerStat->turret_types = null;
            }
        }
        else {
            if($attackerTurretStats)
            {
                // run through existing stats
                $oldTurretsValues = json_decode($attackerStat->turret_types);
                if($oldTurretsValues)
                {
                    foreach($oldTurretsValues as $oldTurretValues)
                    {
                        // run through new values
                        foreach($attackerTurretStats as $newTurretValues)
                        {
                            // compare ids
                            if($oldTurretValues->turret_id == $newTurretValues->turret_id)
                            {
                                $oldTurretValues->lost += $newTurretValues->lost;
                                $oldTurretValues->destroyed += $newTurretValues->destroyed;
                            }
                        }

                    }
                    // save updated stats
                    $attackerStat->turret_types = json_encode($oldTurretsValues);
                }

            }

        }
        if($attackerStat->resources_types == null)
        {
            // first ever entry
            if($attackerResourceStats)
            {
                $attackerStat->resources_types = json_encode($attackerResourceStats);
            } else {
                $attackerStat->resources_types = null;
            }
        }
        else {
            if($attackerResourceStats)
            {
                // run through existing stats
                $oldResourcesValues = json_decode($attackerStat->resources_types);
                if($oldResourcesValues)
                {
                    foreach($oldResourcesValues as $oldResourceValues)
                    {
                        // run through new values
                        foreach($attackerResourceStats as $newResourceValues)
                        {
                            // compare ids
                            if($oldResourceValues->res_id == $newResourceValues->res_id)
                            {
                                $oldResourceValues->lost += $newResourceValues->lost;
                                $oldResourceValues->caught += $newResourceValues->caught;
                            }
                        }

                    }
                    // save updated stats
                    $attackerStat->resources_types = json_encode($oldResourcesValues);
                }

            }

        }

        // update defender stats
        if($defenderStat->ship_types == null)
        {
            // first ever entry
            if($defenderShipStats)
            {
                $defenderStat->ship_types = json_encode($defenderShipStats);
            } else {
                $defenderStat->ship_types = null;
            }
        }
        else {
            // run through existing stats
            $oldShipsValues = json_decode($defenderStat->ship_types);
            foreach($oldShipsValues as $oldShipValues)
            {
                // run through new values
                foreach($defenderShipStats as $newShipValues)
                {
                    // compare ids
                    if($oldShipValues->ship_id == $newShipValues->ship_id)
                    {
                        $oldShipValues->lost += $newShipValues->lost;
                        $oldShipValues->destroyed += $newShipValues->destroyed;
                    }
                }

            }
            // save updated stats
            $defenderStat->ship_types = json_encode($oldShipsValues);
        }
        if($defenderStat->turret_types == null)
        {
            // first ever entry
            if($defenderTurretStats)
            {
                $defenderStat->turret_types = json_encode($defenderTurretStats);
            } else {
                $defenderStat->turret_types = null;
            }
        }
        else {
            if($defenderTurretStats)
            {
                // run through existing stats
                $oldTurretsValues = json_decode($defenderStat->turret_types);
                if($oldTurretsValues)
                {
                    foreach($oldTurretsValues as $oldTurretValues)
                    {
                        // run through new values
                        foreach($defenderTurretStats as $newTurretValues)
                        {
                            // compare ids
                            if($oldTurretValues->turret_id == $newTurretValues->turret_id)
                            {
                                $oldTurretValues->lost += $newTurretValues->lost;
                                $oldTurretValues->destroyed += $newTurretValues->destroyed;
                            }
                        }

                    }
                    // save updated stats
                    $defenderStat->turret_types = json_encode($oldTurretsValues);
                }

            }

        }
        if($defenderStat->resources_types == null)
        {
            // first ever entry
            if($defenderResourceStats)
            {
                $defenderStat->resources_types = json_encode($defenderResourceStats);
            } else {
                $defenderStat->resources_types = null;
            }

        }
        else {
            if($defenderResourceStats)
            {
                // run through existing stats
                $oldResourcesValues = json_decode($defenderStat->resources_types);
                if($oldResourcesValues)
                {
                    foreach($oldResourcesValues as $oldResourceValues)
                    {
                        // run through new values
                        foreach($defenderResourceStats as $newResourceValues)
                        {
                            // compare ids
                            if($oldResourceValues->res_id == $newResourceValues->res_id)
                            {
                                $oldResourceValues->lost += $newResourceValues->lost;
                                $oldResourceValues->caught += $newResourceValues->caught;
                            }
                        }

                    }
                    // save updated stats
                    $defenderStat->resources_types = json_encode($oldResourcesValues);
                }

            }

        }

        // update attacker ally stats | if
        if($attackerAllyStat)
        {
            if($attackerAllyStat->ship_types == null)
            {
                // first ever entry
                if($attackerShipStats)
                {
                    $attackerAllyStat->ship_types = json_encode($attackerShipStats);
                } else {
                    $attackerAllyStat->ship_types = null;
                }
            }
            else {
                if($attackerShipStats)
                {
                    // run through existing stats
                    $oldShipsValues = json_decode($attackerAllyStat->ship_types);
                    if($oldShipsValues)
                    {
                        foreach($oldShipsValues as $oldShipValues)
                        {
                            // run through new values
                            foreach($attackerShipStats as $newShipValues)
                            {
                                // compare ids
                                if($oldShipValues->ship_id == $newShipValues->ship_id)
                                {
                                    $oldShipValues->lost += $newShipValues->lost;
                                    $oldShipValues->destroyed += $newShipValues->destroyed;
                                }
                            }

                        }
                        // save updated stats
                        $attackerAllyStat->ship_types = json_encode($oldShipsValues);
                    }

                }

            }
            if($attackerAllyStat->turret_types == null)
            {
                // first ever entry
                if($attackerTurretStats)
                {
                    $attackerAllyStat->turret_types = json_encode($attackerTurretStats);
                } else {
                    $attackerAllyStat->turret_types = null;
                }
            }
            else {
                if($attackerTurretStats)
                {
                    // run through existing stats
                    $oldTurretsValues = json_decode($attackerAllyStat->turret_types);
                    if($oldTurretsValues)
                    {
                        foreach($oldTurretsValues as $oldTurretValues)
                        {
                            // run through new values
                            foreach($attackerTurretStats as $newTurretValues)
                            {
                                // compare ids
                                if($oldTurretValues->turret_id == $newTurretValues->turret_id)
                                {
                                    $oldTurretValues->lost += $newTurretValues->lost;
                                    $oldTurretValues->destroyed += $newTurretValues->destroyed;
                                }
                            }

                        }
                        // save updated stats
                        $attackerAllyStat->turret_types = json_encode($oldTurretsValues);
                    }

                }

            }
            if($attackerAllyStat->resources_types == null)
            {
                // first ever entry
                if($attackerResourceStats)
                {
                    $attackerAllyStat->resources_types = json_encode($attackerResourceStats);
                } else {
                    $attackerAllyStat->resources_types = null;
                }
            }
            else {
                if($attackerResourceStats)
                {
                    // run through existing stats
                    $oldResourcesValues = json_decode($attackerAllyStat->resources_types);
                    if($oldResourcesValues)
                    {
                        foreach($oldResourcesValues as $oldResourceValues)
                        {
                            // run through new values
                            foreach($attackerResourceStats as $newResourceValues)
                            {
                                // compare ids
                                if($oldResourceValues->res_id == $newResourceValues->res_id)
                                {
                                    $oldResourceValues->lost += $newResourceValues->lost;
                                    $oldResourceValues->caught += $newResourceValues->caught;
                                }
                            }

                        }
                        // save updated stats
                        $attackerAllyStat->resources_types = json_encode($oldResourcesValues);
                    }

                }

            }
        }


        // update defender ally stats | if
        if($defenderAllyStat)
        {
            if($defenderAllyStat->ship_types == null)
            {
                // first ever entry
                if($defenderShipStats)
                {
                    $defenderAllyStat->ship_types = json_encode($defenderShipStats);
                } else {
                    $defenderAllyStat->ship_types = null;
                }
            }
            else {
                // run through existing stats
                $oldShipsValues = json_decode($defenderAllyStat->ship_types);
                foreach($oldShipsValues as $oldShipValues)
                {
                    // run through new values
                    foreach($defenderShipStats as $newShipValues)
                    {
                        // compare ids
                        if($oldShipValues->ship_id == $newShipValues->ship_id)
                        {
                            $oldShipValues->lost += $newShipValues->lost;
                            $oldShipValues->destroyed += $newShipValues->destroyed;
                        }
                    }

                }
                // save updated stats
                $defenderAllyStat->ship_types = json_encode($oldShipsValues);
            }
            if($defenderAllyStat->turret_types == null)
            {
                // first ever entry
                if($defenderTurretStats)
                {
                    $defenderAllyStat->turret_types = json_encode($defenderTurretStats);
                } else {
                    $defenderAllyStat->turret_types = null;
                }
            }
            else {
                if($defenderTurretStats)
                {
                    // run through existing stats
                    $oldTurretsValues = json_decode($defenderAllyStat->turret_types);
                    if($oldTurretsValues)
                    {
                        foreach($oldTurretsValues as $oldTurretValues)
                        {
                            // run through new values
                            foreach($defenderTurretStats as $newTurretValues)
                            {
                                // compare ids
                                if($oldTurretValues->turret_id == $newTurretValues->turret_id)
                                {
                                    $oldTurretValues->lost += $newTurretValues->lost;
                                    $oldTurretValues->destroyed += $newTurretValues->destroyed;
                                }
                            }

                        }
                        // save updated stats
                        $defenderAllyStat->turret_types = json_encode($oldTurretsValues);
                    }

                }

            }
            if($defenderAllyStat->resources_types == null)
            {
                // first ever entry
                if($defenderResourceStats)
                {
                    $defenderAllyStat->resources_types = json_encode($defenderResourceStats);
                } else {
                    $defenderAllyStat->resources_types = null;
                }

            }
            else {
                if($defenderResourceStats)
                {
                    // run through existing stats
                    $oldResourcesValues = json_decode($defenderAllyStat->resources_types);
                    if($oldResourcesValues)
                    {
                        foreach($oldResourcesValues as $oldResourceValues)
                        {
                            // run through new values
                            foreach($defenderResourceStats as $newResourceValues)
                            {
                                // compare ids
                                if($oldResourceValues->res_id == $newResourceValues->res_id)
                                {
                                    $oldResourceValues->lost += $newResourceValues->lost;
                                    $oldResourceValues->caught += $newResourceValues->caught;
                                }
                            }

                        }
                        // save updated stats
                        $defenderAllyStat->resources_types = json_encode($oldResourcesValues);
                    }

                }

            }
        }


        $attackerStat->attacks += 1;
        $attackerStat->save();
        $defenderStat->defends += 1;
        $defenderStat->save();
        if($attackerAllyStat)
        {
            $attackerAllyStat->attacks += 1;
            $attackerAllyStat->save();
        }
        if($defenderAllyStat)
        {
            $defenderAllyStat->defends += 1;
            $defenderAllyStat->save();
        }

    }
}
