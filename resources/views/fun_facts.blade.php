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
                <table class="table table-striped artifacts-table">
                    <thead>
                        <tr>
                            <th scope='col'>@sortablelink('team')</th>
                            <th scope='col'>@sortablelink('class')</th>
                            <th scope='col'>@sortablelink('affiliate')</th>
                            <th scope='col'>@sortablelink('league')</th>
                            <th scope='col'>City</th>
                            <th scope='col'>@sortablelink('state')</th>
                            <th scope='col'>@sortablelink('country')</th>
                            <th scope='col'>@sortablelink('founded')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ml_teams as $ml_team)
                            <tr>
                                <td class="table-text"><a href="/funfacts/mlt/{{$ml_team->id}}">{{$ml_team->team}}</a></td>
                                <td class="table-text">{{$ml_team->class}}</td>
                                <td class="table-text">{{$ml_team->affiliate}}</td>
                                <td class="table-text">{{$ml_team->league}}</td>
                                <td class="table-text">{{$ml_team->city}}</td>
                                <td class="table-text">{{$ml_team->state}}</td>
                                <td class="table-text">{{$ml_team->country}}</td>
                                <td class="table-text">{{$ml_team->founded}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $ml_teams->appends(\Request::except('page'))->render() }}
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