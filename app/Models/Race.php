<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getOneById($id)
    {
        return Race::find($id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
