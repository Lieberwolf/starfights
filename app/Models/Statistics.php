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

        // add values to all statistics (care about order)
        // get the ship loss of the attacker -> destroyed by defender
        $attackerShipLoss = [];
        foreach($attacker["ship"] as $key => $ship)
        {
            $temp = new \stdClass();
            $temp->ship_id = $ship->ship_id;
            $temp->ship_name = $ship->ship_name;
            $temp->lost = $ship->amount - $ship->newAmount;
            array_push($attackerShipLoss, $temp);
        }
        // just needed for first run
        $attackerTurretLoss = false;
        if($defender["turrets"])
        {
            $attackerTurretLoss = [];
            foreach($defender["turrets"] as $key => $turret)
            {
                $temp = new \stdClass();
                $temp->turret_id = $turret->turret_id;
                $temp->turret_name = $turret->turret_name;
                $temp->lost = 0;
                array_push($attackerTurretLoss, $temp);
            }
        }

        // get the ship loss of the defender -> destroyed by attacker
        $defenderShipLoss = [];
        foreach($defender["ship"] as $key => $ship)
        {
            $temp = new \stdClass();
            $temp->ship_id = $ship->ship_id;
            $temp->ship_name = $ship->ship_name;
            $temp->lost = $ship->amount - $ship->newAmount;
            array_push($defenderShipLoss, $temp);
        }
        // get the turret loss of the defender -> destroyed by attacker
        $defenderTurretLoss = false;
        if($defender["turrets"])
        {
            $defenderTurretLoss = [];
            foreach($defender["turrets"] as $key => $turret)
            {
                $temp = new \stdClass();
                $temp->turret_id = $turret->turret_id;
                $temp->turret_name = $turret->turret_name;
                $temp->lost = $turret->amount - $turret->newAmount;
                array_push($defenderTurretLoss, $temp);
            }
        }


        // get the stolen resources from the defender -> by attacker
        $cargo = json_decode($cargo);


        // apply on users stats
        // attacker first
        if($attackerStat->ship_types == null)
        {
            // first run through so add the destroyed field
            foreach($attackerShipLoss as $shipAtt)
            {
                $shipAtt->destroyed = 0;
                // second run through so add destroyed amount
                foreach($defenderShipLoss as $shipDef)
                {
                    if($shipAtt->ship_id == $shipDef->ship_id)
                    {
                        $shipAtt->destroyed = $shipDef->lost;
                    }
                }
            }

            // now add to stats
            $attackerStat->ship_types = json_encode($attackerShipLoss);
        }
        else {
            $oldShipStats = json_decode($attackerStat->ship_types);

                foreach($attackerShipLoss as $shipAtt)
                {
                    $shipAtt->destroyed = 0;
                    // second run through so add destroyed amount
                    foreach($defenderShipLoss as $shipDef)
                    {
                        foreach($oldShipStats as $oldShipStat)
                        {
                        if($shipAtt->ship_id == $shipDef->ship_id && $shipAtt->ship_id == $oldShipStat->ship_id)
                        {
                            // new value        =   Defender loss + OldStatistic
                            $shipAtt->destroyed = $shipDef->lost + $oldShipStat->destroyed;
                            $shipAtt->lost += $oldShipStat->lost;
                        }
                    }
                }
            }

            // now add to stats
            $attackerStat->ship_types = json_encode($attackerShipLoss);
        }

        // write turrets to stats
        if($attackerTurretLoss) {
            if ($attackerStat->turret_types == null) {
                // first run through so add the destroyed field
                if ($attackerTurretLoss) {
                    foreach ($attackerTurretLoss as $turretAtt) {
                        $turretAtt->destroyed = 0;
                        // second run through so add destroyed amount
                        foreach ($defenderTurretLoss as $turretDef) {
                            if ($turretAtt->turret_id == $turretDef->turret_id) {
                                $turretAtt->destroyed = $turretDef->lost;
                            }
                        }
                    }
                }

                // now add to stats
                $attackerStat->turret_types = json_encode($attackerTurretLoss);
            }
            else {
                $oldTurretStats = json_decode($attackerStat->turret_types);

                foreach ($attackerTurretLoss as $turretAtt) {
                    $turretAtt->destroyed = 0;
                    // second run through so add destroyed amount
                    foreach ($defenderTurretLoss as $turretDef) {
                        foreach ($oldTurretStats as $oldTurretStat) {
                            if ($turretAtt->turret_id == $turretDef->turret_id && $turretAtt->turret_id == $oldTurretStat->turret_id) {
                                // new value        =   Defender loss + OldStatistic
                                $turretAtt->destroyed = $turretDef->lost + $oldTurretStat->destroyed;
                            }
                        }
                    }
                }


                // now add to stats
                $attackerStat->turret_types = json_encode($attackerTurretLoss);
            }
        }

        // write resource stats
        if($attackerStat->resources_types == null)
        {
            $attackerCargoStats = [];
            foreach($cargo as $key => $res)
            {
                $temp = new \stdClass();
                $temp->id = $key;
                $temp->caught = $res;
                $temp->lost = 0;
                array_push($attackerCargoStats, $temp);
            }
            $attackerStat->resources_types = json_encode($attackerCargoStats);
        }
        else {
            $oldRes = json_decode($attackerStat->resources_types);
            foreach($oldRes as $key => $res)
            {
                foreach($cargo as $keyC => $resC)
                {
                    if($res->id == $keyC)
                    {
                        $res->caught += $resC;
                    }
                }
            }
            $attackerStat->resources_types = json_encode($oldRes);
        }


        // defender part
        if($defenderStat->ship_types == null)
        {
            // first run through so add the destroyed field
            foreach($defenderShipLoss as $shipDef)
            {
                $shipDef->destroyed = 0;
                // second run through so add destroyed amount
                foreach($attackerShipLoss as $shipAtt)
                {
                    if($shipAtt->ship_id == $shipDef->ship_id)
                    {
                        $shipDef->destroyed = $shipAtt->lost;
                    }
                }
            }

            // now add to stats
            $defenderStat->ship_types = json_encode($defenderShipLoss);
        }
        else {
            $oldShipStats = json_decode($defenderStat->ship_types);
            foreach($oldShipStats as $oldShipStat)
            {
                foreach($defenderShipLoss as $shipDef)
                {
                    $shipDef->lost = $oldShipStat->lost;
                    // second run through so add destroyed amount
                    foreach($attackerShipLoss as $shipAtt)
                    {

                        if($shipDef->ship_id == $shipAtt->ship_id && $shipDef->ship_id == $oldShipStat->ship_id)
                        {
                            // new value        =   Defender loss + OldStatistic
                            $shipDef->lost += $shipAtt->destroyed;
                            $shipDef->destroyed = $shipAtt->lost + $oldShipStat->destroyed;
                        }
                    }
                }
            }

            // now add to stats
            $defenderStat->ship_types = json_encode($defenderShipLoss);
        }
        // write turrets to stats
        if($defenderTurretLoss)
        {
            if($defenderStat->turret_types == null)
            {
                // first run through so add the destroyed field
                foreach($defenderTurretLoss as $turretDef)
                {
                    $turretDef->destroyed = 0;
                    // second run through so add destroyed amount
                    foreach($attackerTurretLoss as $turretAtt)
                    {
                        if($turretAtt->turret_id == $turretDef->turret_id)
                        {
                            $turretDef->destroyed = $turretAtt->lost;
                        }
                    }
                }

                // now add to stats
                $defenderStat->turret_types = json_encode($defenderTurretLoss);
            }
            else {
                $oldTurretStats = json_decode($defenderStat->turret_types);
                foreach ($oldTurretStats as $oldTurretStat) {
                    foreach ($defenderTurretLoss as $turretDef) {
                        if($turretDef->turret_id == $oldTurretStat->turret_id)
                        {
                            $turretDef->lost = $oldTurretStat->lost;
                        }
                        // second run through so add destroyed amount
                        foreach ($attackerTurretLoss as $turretAtt) {

                            if ($turretDef->turret_id == $turretAtt->turret_id && $turretDef->turret_id == $oldTurretStat->turret_id) {
                                // new value        =   Attacker destroyed + OldStatistic
                                dd($turretAtt->destroyed);

                                $turretDef->lost += $turretAtt->destroyed;
                            }
                        }
                    }
                }


                // now add to stats
                $defenderStat->turret_types = json_encode($defenderTurretLoss);
            }

        }
        // write resource stats
        if($defenderStat->resources_types == null)
        {
            $defenderCargoStats = [];
            foreach($cargo as $key => $res)
            {
                $temp = new \stdClass();
                $temp->id = $key;
                $temp->caught = 0;
                $temp->lost = $res;
                array_push($defenderCargoStats, $temp);
            }
            $defenderStat->resources_types = json_encode($defenderCargoStats);
        }
        else {
            $oldRes = json_decode($defenderStat->resources_types);
            foreach($oldRes as $key => $res)
            {
                foreach($cargo as $keyC => $resC)
                {
                    if($res->id == $keyC)
                    {
                        $res->lost += $resC;
                    }
                }
            }
            $defenderStat->resources_types = json_encode($oldRes);
        }

        // apply on alliances stats (if)
        if($attackerAllyStat)
        {
            // attacker first
            if($attackerAllyStat->ship_types == null)
            {
                // first run through so add the destroyed field
                foreach($attackerShipLoss as $shipAtt)
                {
                    $shipAtt->destroyed = 0;
                    // second run through so add destroyed amount
                    foreach($defenderShipLoss as $shipDef)
                    {
                        if($shipAtt->ship_id == $shipDef->ship_id)
                        {
                            $shipAtt->destroyed = $shipDef->lost;
                        }
                    }
                }

                // now add to stats
                $attackerAllyStat->ship_types = json_encode($attackerShipLoss);
            }
            else {
                $oldShipStats = json_decode($attackerAllyStat->ship_types);
                foreach($oldShipStats as $oldShipStat)
                {
                    foreach($attackerShipLoss as $shipAtt)
                    {
                        if($shipAtt->ship_id == $oldShipStat->ship_id)
                        {
                            $shipAtt->destroyed = $oldShipStat->destroyed;
                        }
                        // second run through so add destroyed amount
                        foreach($defenderShipLoss as $shipDef)
                        {

                            if($shipAtt->ship_id == $shipDef->ship_id && $shipAtt->ship_id == $oldShipStat->ship_id)
                            {
                                // new value        =   Defender loss + OldStatistic
                                $shipAtt->destroyed += $shipDef->lost;
                                $shipAtt->lost = $shipDef->destroyed + $oldShipStat->lost;
                            }
                        }
                    }
                }

                // now add to stats
                $attackerAllyStat->ship_types = json_encode($attackerShipLoss);
            }
            // write turrets to stats
            if($attackerTurretLoss) {
                if ($attackerAllyStat->turret_types == null) {
                    // first run through so add the destroyed field
                    foreach ($attackerTurretLoss as $turretAtt) {
                        $turretAtt->destroyed = 0;
                        // second run through so add destroyed amount
                        foreach ($defenderTurretLoss as $turretDef) {
                            if ($turretAtt->turret_id == $turretDef->turret_id) {
                                $turretAtt->destroyed = $turretDef->lost;
                            }
                        }
                    }

                    // now add to stats
                    $attackerAllyStat->turret_types = json_encode($attackerTurretLoss);
                }
                else {
                    $oldTurretStats = json_decode($attackerAllyStat->turret_types);
                    foreach ($oldTurretStats as $oldTurretStat) {
                        foreach ($attackerTurretLoss as $turretAtt) {
                            if($turretAtt->turret_id == $oldTurretStat->turret_id)
                            {
                                $turretAtt->destroyed = $oldTurretStat->destroyed;
                            }
                            // second run through so add destroyed amount
                            foreach ($defenderTurretLoss as $turretDef) {

                                if ($turretAtt->turret_id == $turretDef->turret_id && $turretAtt->turret_id == $oldTurretStat->turret_id) {
                                    // new value        =   Defender loss + OldStatistic
                                    $turretAtt->destroyed += $turretDef->lost;
                                }
                            }
                        }
                    }


                    // now add to stats
                    $attackerAllyStat->turret_types = json_encode($attackerTurretLoss);
                }
            }
            // write resource stats
            if($attackerAllyStat->resources_types == null)
            {
                $attackerCargoStats = [];
                foreach($cargo as $key => $res)
                {
                    $temp = new \stdClass();
                    $temp->id = $key;
                    $temp->caught = $res;
                    $temp->lost = 0;
                    array_push($attackerCargoStats, $temp);
                }
                $attackerAllyStat->resources_types = json_encode($attackerCargoStats);
            }
            else {
                $oldRes = json_decode($attackerAllyStat->resources_types);
                foreach($oldRes as $key => $res)
                {
                    foreach($cargo as $keyC => $resC)
                    {
                        if($res->id == $keyC)
                        {
                            $res->caught += $resC;
                        }
                    }
                }
                $attackerAllyStat->resources_types = json_encode($oldRes);
            }

            // finally save
            $attackerAllyStat->save();
        }
        $attackerStat->save();

        // defender part
        dd($defenderStat);
        if($defenderAllyStat)
        {
            if($defenderAllyStat->ship_types == null)
            {
                // first run through so add the destroyed field
                foreach($defenderShipLoss as $shipDef)
                {
                    $shipDef->destroyed = 0;
                    // second run through so add destroyed amount
                    foreach($attackerShipLoss as $shipAtt)
                    {
                        if($shipAtt->ship_id == $shipDef->ship_id)
                        {
                            $shipDef->destroyed = $shipAtt->lost;
                        }
                    }
                }

                // now add to stats
                $defenderAllyStat->ship_types = json_encode($defenderShipLoss);
            }
            else {
                $oldShipStats = json_decode($defenderAllyStat->ship_types);
                foreach($oldShipStats as $oldShipStat)
                {
                    foreach($defenderShipLoss as $shipDef)
                    {
                        if($shipDef->ship_id == $oldShipStat->ship_id)
                        {
                            $shipDef->lost = $oldShipStat->lost;
                        }
                        // second run through so add destroyed amount
                        foreach($attackerShipLoss as $shipAtt)
                        {
                            if($shipDef->ship_id == $shipAtt->ship_id && $shipDef->ship_id == $oldShipStat->ship_id)
                            {
                                // new value        =   Defender loss + OldStatistic
                                $shipDef->lost += $shipAtt->destroyed;
                                $shipDef->destroyed = $shipAtt->lost + $oldShipStat->destroyed;
                            }
                        }
                    }
                }

                // now add to stats
                $defenderAllyStat->ship_types = json_encode($defenderShipLoss);
            }
            // write turrets to stats
            if($defenderTurretLoss)
            {
                if($defenderAllyStat->turret_types == null)
            {
                // first run through so add the destroyed field
                foreach($defenderTurretLoss as $turretDef)
                {
                    $turretDef->destroyed = 0;
                    // second run through so add destroyed amount
                    foreach($attackerTurretLoss as $turretAtt)
                    {
                        if($turretAtt->turret_id == $turretDef->turret_id)
                        {
                            $turretDef->destroyed = $turretAtt->lost;
                        }
                    }
                }

                // now add to stats
                $defenderAllyStat->turret_types = json_encode($defenderTurretLoss);
            }
                else {
                    $oldTurretStats = json_decode($defenderAllyStat->turret_types);
                    foreach ($oldTurretStats as $oldTurretStat) {
                        foreach ($defenderTurretLoss as $turretDef) {
                            if($turretDef->ship_id == $oldTurretStat->ship_id)
                            {
                                $turretDef->lost = $oldTurretStat->lost;
                            }
                            // second run through so add destroyed amount
                            foreach ($attackerTurretLoss as $turretAtt) {
                                if ($turretDef->turret_id == $turretAtt->turret_id && $turretDef->turret_id == $oldTurretStat->turret_id) {
                                    // new value        =   Attacker destroyed + OldStatistic
                                    $turretDef->lost += $turretAtt->destroyed;
                                }
                            }
                        }
                    }


                    // now add to stats
                    $defenderAllyStat->turret_types = json_encode($defenderTurretLoss);
                }
            }
            // write resource stats
            if($defenderAllyStat->resources_types == null)
            {
                $defenderCargoStats = [];
                foreach($cargo as $key => $res)
                {
                    $temp = new \stdClass();
                    $temp->id = $key;
                    $temp->caught = 0;
                    $temp->lost = $res;
                    array_push($defenderCargoStats, $temp);
                }
                $defenderAllyStat->resources_types = json_encode($defenderCargoStats);
            }
            else {
                $oldRes = json_decode($defenderAllyStat->resources_types);
                foreach($oldRes as $key => $res)
                {
                    foreach($cargo as $keyC => $resC)
                    {
                        if($res->id == $keyC)
                        {
                            $res->lost += $resC;
                        }
                    }
                }
                $attackerStat->resources_types = json_encode($oldRes);
            }

            // finally save
            $defenderAllyStat->save();
        }
        dd($defenderStat);
        $defenderStat->save();

    }
}
