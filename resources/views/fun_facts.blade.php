@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Fun Facts</h2>

        <ul class="nav nav-tabs" id="factsTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="winners-tab" data-toggle="tab" href="#winners" role="tab" aria-controls="winners"
              aria-selected="true">World Series Winners</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="mlt-tab" data-toggle="tab" href="#mlt" role="tab" aria-controls="mlt"
              aria-selected="true">Minor League Teams</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cities-tab" data-toggle="tab" href="#cities" role="tab" aria-controls="cities"
              aria-selected="true">Player Cities</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="injuries-tab" data-toggle="tab" href="#injuries" role="tab" aria-controls="injuries"
              aria-selected="true">Player Injuries</a>
           </li>
        </ul>

        <div class="tab-content" id="statsTabsContent">

            <div class="tab-pane fade show active" id="winners" role="tabpanel" aria-labelledby="winners-tab">
                <div class="ml-3 mt-3 mb-2">
                   Winners
                </div>
                <ul>
                    @foreach ($world_series_winners as $year => $team)
                        <li>
                            {{ $year }} {{ $team }}
                        </li>
                    @endforeach 
                </ul>
            </div>

            <div class="tab-pane fade" id="mlt" role="tabpanel" aria-labelledby="mlt-tab">
                <div class="ml-3 mt-3 mb-2">
                    Minor league teams that rookies are currently playing with / players have played with:
                </div>
 
                <ul>
                    @foreach ($ml_teams as $team)
                        <li>
                            {{ $team['team'] }}
                        </li>
                    @endforeach 
                </ul>

            </div>

            <div class="tab-pane fade" id="cities" role="tabpanel" aria-labelledby="cities-tab">
                <div class="ml-3 mt-3 mb-2">
                    Hometown of players:
                </div>
                <ul>
                    @foreach ($player_cities as $player)
                        <li>
                            {{ $player->city }},
                            @if ($player->country == 'US')
                                {{ $player->state }}
                            @else
                                {{ $player->country }}
                            @endif
                            ({{ $player->count }})
                        </li>
                    @endforeach 
                </ul>
            </div>

            <div class="tab-pane fade" id="injuries" role="tabpanel" aria-labelledby="injuries-tab">
                <div class="ml-3 mt-3 mb-2">
                    <div>

                        <h6>Common Injuries</h6>
                        <ul>
                            @foreach ($common_injuries as $injury => $count)
                                <li>{{ $injury }} : {{ $count }}</li>
                            @endforeach
                        </ul>

                        @foreach ($player_injuries as $player => $injuries)
                            <h6>{{ $player }}</h6>
                            <ul>
                                @foreach ($injuries as $injury)
                                    <li>{{ $injury }}</li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
