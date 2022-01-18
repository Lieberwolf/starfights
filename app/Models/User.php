<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isAdmin',
        'race_id',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function planets()
    {
        return $this->hasMany(Planet::class);
    }

    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    public function race()
    {
        return $this->hasOne(Race::class);
    }

    public function statistics()
    {
        return $this->hasOne(Statistics::class);
    }

    public static function getAllUserProfiles() {
        return Profile::all();
    }

    public static function getAllUserPoints($user) {

    }

    public static function getHighscoreList(){
        $result = DB::select(
            DB::raw(
            "(select
                pr.user_id,
	            pr.nickname,
	            sum(b.points * i.level) as Planetenpunkte,
	            (Select sum(k.level*r.points) from knowledge as k left join research as r on r.id = k.research_id where k.user_id = pr.user_id) as Forschungspunkte
            from profiles as pr
            left join
	            planets as p on p.user_id = pr.user_id
            inner join
                infrastructures as i on p.id = i.planet_id
            left join
                buildings as b on b.id = i.building_id
            Group by pr.user_id, pr.nickname
            order by Planetenpunkte desc, Forschungspunkte desc
            Limit 100)
            ")
        );
        return $result;
    }
}
