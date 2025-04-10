<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FootballMatchService;

class FetchFootballMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:football-matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch football matches from the API';

    /**
     * Execute the console command.
     */
    public function handle(FootballMatchService $footballMatchService)
    {
        try {
            $footballMatchService->fetchAndStoreMatches();
            $this->info('Football matches fetched successfully!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
