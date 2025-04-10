<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $fillable = [
        'league_name',
        'country_name',
        'home_team',
        'away_team',
        'home_score',
        'away_score',
        'status',
    ];
}
