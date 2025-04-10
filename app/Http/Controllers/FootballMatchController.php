<?php

namespace App\Http\Controllers;

use App\Services\FootballMatchService;

class FootballMatchController
{
    protected $footballMatchService;

    /**
     * Constructor
     *
     * @param FootballMatchService $footballMatchService
     */
    public function __construct(FootballMatchService $footballMatchService)
    {
        $this->footballMatchService = $footballMatchService;
    }

    /**
     * Display the football matches
     *
     * @return View
     */
    public function index()
    {
        $matches = $this->footballMatchService->getMatchesGroupedByLeague();
        $countryFlags = $this->footballMatchService->countryFlags();

        return view('matches.index', compact('matches', 'countryFlags'));
    }

    /**
     * Get updates for the football matches
     *
     * @return JsonResponse
     */
    public function getUpdates()
    {
        $matches = $this->footballMatchService->getMatchesGroupedByLeague();

        return response()->json($matches);
    }
}
