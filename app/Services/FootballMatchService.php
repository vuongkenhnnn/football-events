<?php

namespace App\Services;

use App\Models\FootballMatch;
use App\Repositories\Interfaces\FootballMatchRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class FootballMatchService
{
    private $footballMatchRepository;

    /**
     * Constructor
     *
     * @param FootballMatchRepositoryInterface $footballMatchRepository
     */
    public function __construct(FootballMatchRepositoryInterface $footballMatchRepository)
    {
        $this->footballMatchRepository = $footballMatchRepository;
    }

    /**
     * Get all matches grouped by league
     *
     * @return array
     */
    public function getMatchesGroupedByLeague()
    {
        try {
            $matches = $this->footballMatchRepository->getMatchesGroupedByLeague();

            return $matches;
        } catch (\Exception $e) {
            Log::error('Error getting matches: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch matches from API and store in database
     *
     * @return void
     */
    public function fetchAndStoreMatches(): void
    {
        try {
            $response = Http::get('https://etex.s3.eu-central-1.amazonaws.com/etsr/football.json');

            if ($response->successful()) {
                $matches = $response->json();
                $processedMatches = [];

                foreach ($matches['events'] as $match) {
                    $processedMatch = $this->processMatch($match);
                    if ($processedMatch) {
                        $processedMatches[] = $processedMatch;
                    }
                }

                if (!empty($processedMatches)) {
                    FootballMatch::upsert(
                        $processedMatches,
                        ['id'],
                        [
                            'id',
                            'status',
                            'home_team',
                            'away_team',
                            'home_score',
                            'away_score',
                            'updated_at'
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching matches: ' . $e->getMessage());
        }
    }

    /**
     * Process match data and return array for upsert
     *
     * @param array $match
     *
     * @return array|null
     */
    private function processMatch(array $match): ?array
    {
        try {
            $countryName = $match['league']['country_name'] ?? '';
            $leagueName = $match['league']['name'] ?? '';
            $homeTeam = $match['eventParticipant'][1] ?? [];
            $awayTeam = $match['eventParticipant'][2] ?? [];
            $homeScore = $homeTeam['result']['runningscore'] ?? 0;
            $awayScore = $awayTeam['result']['runningscore'] ?? 0;
            $status = $match['status']['name'] ?? 'NS';

            return [
                'id' => $match['id'],
                'country_name' => $countryName,
                'league_name' => $leagueName,
                'home_team' => $homeTeam['name'] ?? 'Unknown',
                'away_team' => $awayTeam['name'] ?? 'Unknown',
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status' => $status,
                'updated_at' => now(),
            ];
        } catch (\Exception $e) {
            Log::error('Error processing match: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Load country flags from JSON file
     *
     * @return array
     */
    public function countryFlags()
    {
        try {
            $jsonPath = resource_path('data/country_flags.json');
            return json_decode(File::get($jsonPath), true) ?? [];
        } catch (\Exception $e) {
            Log::error('Error loading country flags: ' . $e->getMessage());
            return [];
        }
    }
}
