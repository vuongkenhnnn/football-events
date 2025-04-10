<?php

namespace App\Repositories\Interfaces;

interface FootballMatchRepositoryInterface
{
    /**
     * Get matches grouped by league with country name
     * Returns data in format: "Country: League"
     *
     * @return array
     */
    public function getMatchesGroupedByLeague();
}
