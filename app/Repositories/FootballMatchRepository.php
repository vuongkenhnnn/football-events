<?php

namespace App\Repositories;

use App\Models\FootballMatch;
use App\Repositories\Interfaces\FootballMatchRepositoryInterface;

class FootballMatchRepository implements FootballMatchRepositoryInterface
{
    /**
     * Get matches grouped by league with country name
     * Returns data in format: "Country: League"
     *
     * @return array
     */
    public function getMatchesGroupedByLeague()
    {
        return FootballMatch::select('*')
            ->orderBy('country_name')
            ->get()
            ->groupBy(function ($match) {
                return $match->country_name . ': ' . $match->league_name;
            })
            ->map(function ($matches) {
                return $matches->map(function ($match) {
                    return [
                        'id' => $match->id,
                        'status' => $match->status ?? '',
                        'home_team' => $match->home_team ?? '',
                        'away_team' => $match->away_team ?? '',
                        'home_score' => $match->home_score ?? 0,
                        'away_score' => $match->away_score ?? 0,
                    ];
                })->toArray();
            })
            ->toArray();
    }
}
