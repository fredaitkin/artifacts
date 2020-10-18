@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Fun Facts</h2>

        <ul class="nav nav-tabs" id="factsTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="mlt-tab" data-toggle="tab" href="#mlt" role="tab" aria-controls="mlt"
              aria-selected="true">Minor League Teams</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cities-tab" data-toggle="tab" href="#cities" role="tab" aria-controls="cities"
              aria-selected="true">Player Cities</a>
            </li>
        </ul>

        <div class="tab-content" id="statsTabsContent">

            <div class="tab-pane fade show active" id="mlt" role="tabpanel" aria-labelledby="mlt-tab">
                <div class="ml-3 mt-3 mb-2">
                    Minor league teams that rookies are currently playing with / have played with:
                </div>
                <ul>
                    @foreach($ml_teams as $ml_team)
                        <li>{{$ml_team}}</li>
                    @endforeach
            </div>

            <div class="tab-pane fade" id="cities" role="tabpanel" aria-labelledby="cities-tab">
                <div class="ml-3 mt-3 mb-2">
                    Hometown of players:
                </div>
                <ul>
                    @foreach($player_cities as $player)
                        <li>
                            {{$player->city}},
                            @if($player->country == 'US')
                                {{$player->state}}
                            @else
                                {{$player->country}}
                            @endif
                            ({{$player->count}})
                        </li>
                    @endforeach
            </div>

        </div>

    </div>

@endsection