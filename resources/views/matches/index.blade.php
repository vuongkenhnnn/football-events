@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/matches.css') }}">
@endpush

@section('content')
    <div class="container container-matches">
        @if (empty($matches))
            <div id="alert-league-container" class="alert alert-info">
                No matches found.
            </div>
        @else
            <div id="matches-container">
                @foreach ($matches as $leagueTitle => $leagueData)
                    <div class="league-container" data-league="{{ $leagueTitle }}">
                        <h3 class="league-header">
                            <span class="flag">{{ $countryFlags[explode(':', $leagueTitle)[0]] ?? '' }}</span>
                            {{ $leagueTitle }}
                        </h3>
                        @foreach ($leagueData as $match)
                            <div class="match-row" data-match-id="{{ $match['id'] }}">
                                <div class="match-status">
                                    <span>⚽ {{ $match['status'] }}</span>
                                </div>
                                <div class="match-teams">
                                    <div class="team">{{ $match['home_team'] }}</div>
                                    <div class="team">{{ $match['away_team'] }}</div>
                                </div>
                                <div class="match-score">
                                    {{ $match['home_score'] }}<br>{{ $match['away_score'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function createLeagueContainer(leagueTitle, matches) {
            const countryFlags = @json($countryFlags);
            const leagueContainer = document.createElement('div');
            leagueContainer.className = 'league-container';
            leagueContainer.setAttribute('data-league', leagueTitle);

            const header = document.createElement('h3');
            header.className = 'league-header';
            const countryName = leagueTitle.split(':')[0].trim();
            const flag = countryFlags[countryName] || '';
            header.innerHTML = `<span class="flag">${flag}</span>${leagueTitle}`;
            leagueContainer.appendChild(header);

            matches.forEach(match => {
                const matchRow = document.createElement('div');
                matchRow.className = 'match-row';
                matchRow.setAttribute('data-match-id', match.id);
                matchRow.innerHTML = `
                <div class="match-status">
                    <span>⚽ ${match.status}</span>
                </div>
                <div class="match-teams">
                    <div class="team">${match.home_team}</div>
                    <div class="team">${match.away_team}</div>
                </div>
                <div class="match-score">
                    ${match.home_score}<br>${match.away_score}
                </div>
            `;
                leagueContainer.appendChild(matchRow);
            });

            return leagueContainer;
        }

        function renderMatches(data) {
            const parentContainer = document.querySelector('.container-matches');

            if (!parentContainer) return;

            const existingAlert = document.getElementById('alert-league-container');
            const existingMatches = document.getElementById('matches-container');
            if (existingAlert) existingAlert.remove();
            if (existingMatches) existingMatches.remove();

            if (!data || Object.keys(data).length === 0) {
                const alertDiv = document.createElement('div');
                alertDiv.id = 'alert-league-container';
                alertDiv.className = 'alert alert-info';
                alertDiv.textContent = 'No matches found.';
                parentContainer.appendChild(alertDiv);
            } else {
                const matchesDiv = document.createElement('div');
                matchesDiv.id = 'matches-container';
                parentContainer.appendChild(matchesDiv);

                Object.entries(data).forEach(([leagueTitle, matches]) => {
                    if (Array.isArray(matches)) {
                        const leagueContainer = createLeagueContainer(leagueTitle, matches);
                        matchesDiv.appendChild(leagueContainer);
                    }
                });
            }
        }

        function fetchUpdates() {
            fetch('/api/matches')
                .then(response => response.json())
                .then(data => {
                    renderMatches(data);
                })
                .catch(error => console.error('Error fetching updates:', error));
        }

        setInterval(fetchUpdates, 10000);
    </script>
@endpush
