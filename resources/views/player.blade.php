@extends('layouts.app')

@section('content')

    <div class="panel-body artifacts-submit-form-div @if(!empty($player->team)) {{$player->team}}-div @endif">

        <h2 class="col-sm-8">{{$title}}</h2>

        @include('common.errors')

        <form action="/player" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="form-group w-75 row">
                @if(!empty($player->photo))
                    <div class="col">
                        <div class="col-sm-8">
                            <img src="{{ asset('storage/images/smalls/'.$player->small_photo) }}" alt="player_photo">
                        </div>
                    </div>
                @endif
                <div class="col">
                    <label for="player" class="col-sm-10 control-label">First Name</label>

                    <div class="col-sm-12">
                        <input type="text" name="first_name" id="first_name" class="form-control" value="@if (old('first_name')) {{old('first_name') }}@elseif (!empty($player->first_name)){{$player->first_name}}@endif">
                    </div>
                </div>
                <div class="col">
                    <label for="last_name" class="col-sm-10 control-label">Last Name</label>

                    <div class="col-sm-12">
                        <input type="text" name="last_name" id="last_name" class="form-control" value="@if (old('last_name')) {{old('last_name') }}@elseif (!empty($player->last_name)){{$player->last_name}}@endif">
                    </div>
                </div>
                <div class="col">
                    <label for="team" class="col-sm-8 control-label">Team</label>

                    <div class="col-sm-12">

                        <select class="form-control" name="team">
                            @foreach($teams as $key => $team)
                                <option value="{{$key}}"  @if (old('team') && old('team') == $key) selected @elseif (!empty($player->team) && ($player->team == $key)) selected @endif>{{$team}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                @if(isset($player->mlb_link)) <a href="https://www.mlb.com/player/{{ $player->mlb_link}}">mlb</a>@endif
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="city" class="col-sm-8 control-label">City</label>

                    <div class="col-sm-8">
                        <input type="text" name="city" class="form-control" value="@if (old('city')) {{old('city')}} @elseif (!empty($player->city)){{$player->city}}@endif">
                    </div>
                </div>
                <div class="col">
                    <label for="state" class="col-sm-8 control-label">State</label>

                    <div class="col-sm-8">

                        <select class="form-control" name="state">
                            @foreach($states as $key => $state)
                                <option value="{{$key}}"  @if (old('state') && old('state') == $key) selected @elseif (!empty($player->state) && ($player->state == $key)) selected @endif>{{$state}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col">
                    <label for="country" class="col-sm-8 control-label">Country</label>
                    <div class="col-sm-8">

                        <select class="form-control" name="country">
                            @foreach($countries as $country)
                                <option value="{{$country}}"  @if (old('country') && old('country') == $country) selected @elseif (!empty($player->country) && ($player->country == $country)) selected @endif>{{$country}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="birthdate" class="col-sm-8 control-label">Birth Date</label>

                    <div class="col-sm-8">
                        <input type="text" name="birthdate" id="player->birthdate" class="form-control" value=@if (old('birthdate')) {{ old('birthdate') }} @elseif (!empty($player->birthdate)) {{$player->birthdate}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="draft_year" class="col-sm-8 control-label">Draft Year</label>

                    <div class="col-sm-8">
                        <input type="text" name="draft_year" id="draft_year" class="form-control" value=@if (old('draft_year')) {{ old('draft_year') }} @elseif (!empty($player->draft_year)) {{$player->draft_year}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="draft_round" class="col-sm-8 control-label">Draft Round</label>

                    <div class="col-sm-8">
                        <input type="text" name="draft_round" id="draft_round" class="form-control" value=@if (old('draft_round')) {{ old('draft_round') }} @elseif (!empty($player->draft_round)) {{$player->draft_round}} @endif>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="draft_position" class="col-sm-8 control-label">Draft Position</label>

                    <div class="col-sm-8">
                        <input type="text" name="draft_position" id="draft_position" class="form-control" value=@if (old('draft_position')) {{ old('draft_position') }} @elseif (!empty($player->draft_position)) {{$player->draft_position}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="debut_year" class="col-sm-8 control-label">Debut Year</label>

                    <div class="col-sm-8">
                        <input type="text" name="debut_year" id="debut_year" class="form-control" value=@if (old('debut_year')) {{ old('debut_year') }} @elseif (!empty($player->debut_year)) {{$player->debut_year}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="position" class="col-sm-8 control-label">Position</label>

                    <div class="col-sm-8">

                        <select class="form-control" id="position" name="position">
                            @foreach($positions as $key => $position)
                                <option value="{{$key}}" @if (old('position') && old('position') == $key) selected @elseif (!empty($player->position) && ($player->position == $key)) selected @endif>{{$position}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>

            <div class="batting-div">
                <div class="form-group row">
                    <div class="col"><div class="col-sm-8"><a id='batting-link' href="#">hide batting stats</a></div></div>
                </div>
                <div class="form-group row batting-row">
                    <div class="col">
                        <label for="average" class="col-sm-8 control-label">Average</label>

                        <div class="col-sm-8">
                            <input type="text" name="average" id="average" class="form-control" value=@if (old('average')) {{ old('average') }} @elseif (isset($player->average)) {{$player->average}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="at_bats" class="col-sm-8 control-label">At Bats</label>

                        <div class="col-sm-8">
                            <input type="text" name="at_bats" id="at_bats" class="form-control" value=@if (old('at_bats')) {{ old('at_bats') }} @elseif (isset($player->at_bats)) {{$player->at_bats}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="home_runs" class="col-sm-8 control-label">Home Runs</label>

                        <div class="col-sm-8">
                            <input type="text" name="home_runs" id="home_runs" class="form-control" value=@if (old('home_runs')) {{ old('home_runs') }} @elseif (isset($player->home_runs)) {{$player->home_runs}} @endif>
                        </div>
                    </div>
                </div>

                <div class="form-group row batting-row">
                    <div class="col">
                        <label for="rbis" class="col-sm-8 control-label">RBIs</label>

                        <div class="col-sm-8">
                            <input type="text" name="rbis" id="rbis" class="form-control" value=@if (old('rbis')) {{ old('rbis') }} @elseif (isset($player->rbis)) {{$player->rbis}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="hits" class="col-sm-8 control-label">Hits</label>

                        <div class="col-sm-8">
                            <input type="text" name="hits" id="hits" class="form-control" value=@if (old('hits')) {{ old('hits') }} @elseif (isset($player->hits)) {{$player->hits}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="runs" class="col-sm-8 control-label">Runs</label>

                        <div class="col-sm-8">
                            <input type="text" name="runs" id="runs" class="form-control" value=@if (old('runs')) {{ old('runs') }} @elseif (isset($player->runs)) {{$player->runs}} @endif>
                        </div>
                    </div>
                </div>

                <div class="form-group row batting-row">
                    <div class="col">
                        <label for="stolen_bases" class="col-sm-8 control-label">Stolen Bases</label>

                        <div class="col-sm-8">
                            <input type="text" name="stolen_bases" id="stolen_bases" class="form-control" value=@if (old('stolen_bases')) {{ old('stolen_bases') }} @elseif (isset($player->stolen_bases)) {{$player->stolen_bases}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="obp" class="col-sm-8 control-label">OBP</label>

                        <div class="col-sm-8">
                            <input type="text" name="obp" id="obp" class="form-control" value=@if (old('obp')) {{ old('obp') }} @elseif (isset($player->obp)) {{$player->obp}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="ops" class="col-sm-8 control-label">OPS</label>

                        <div class="col-sm-8">
                            <input type="text" name="ops" id="ops" class="form-control" value=@if (old('ops')) {{ old('ops') }} @elseif (isset($player->ops)) {{$player->ops}} @endif>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pitching-div">
                <div class="form-group row">
                    <div class="col"><div class="col-sm-8"><a id='pitching-link' href="#">show pitching stats</a></div></div>
                </div>
                <div class="form-group row pitching-row">
                    <div class="col">
                        <label for="era" class="col-sm-8 control-label">ERA</label>

                        <div class="col-sm-8">
                            <input type="text" name="era" id="era" class="form-control" value=@if (old('era')) {{ old('era') }} @elseif (isset($player->era)) {{$player->era}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="games" class="col-sm-8 control-label">Games</label>

                        <div class="col-sm-8">
                            <input type="text" name="games" id="games" class="form-control" value=@if (old('games')) {{ old('games') }} @elseif (isset($player->games)) {{$player->games}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="wins" class="col-sm-8 control-label">Wins</label>

                        <div class="col-sm-8">
                            <input type="text" name="wins" id="wins" class="form-control" value=@if (old('wins')) {{ old('wins') }} @elseif (isset($player->wins)) {{$player->wins}} @endif>
                        </div>
                    </div>
                </div>

               <div class="form-group row pitching-row">
                    <div class="col">
                        <label for="losses" class="col-sm-8 control-label">Losses</label>

                        <div class="col-sm-8">
                            <input type="text" name="losses" id="losses" class="form-control" value=@if (old('losses')) {{ old('losses') }} @elseif (isset($player->losses)) {{$player->losses}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="saves" class="col-sm-8 control-label">Saves</label>

                        <div class="col-sm-8">
                            <input type="text" name="saves" id="saves" class="form-control" value=@if (old('saves')) {{ old('saves') }} @elseif (isset($player->saves)) {{ $player->saves }} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="games_started" class="col-sm-8 control-label">Games Started</label>

                        <div class="col-sm-8">
                            <input type="text" name="games_started" id="games_started" class="form-control" value=@if (old('games_started')) {{ old('games_started') }} @elseif (isset($player->games_started)) {{$player->games_started}} @endif>
                        </div>
                    </div>
                </div>

               <div class="form-group row pitching-row">
                    <div class="col">
                        <label for="innings_pitched" class="col-sm-8 control-label">Innings Pitched</label>

                        <div class="col-sm-8">
                            <input type="text" name="innings_pitched" id="innings_pitched" class="form-control" value=@if (old('innings_pitched')) {{ old('innings_pitched') }} @elseif (isset($player->innings_pitched)) {{$player->innings_pitched}} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="strike_outs" class="col-sm-8 control-label">Strike Outs</label>

                        <div class="col-sm-8">
                            <input type="text" name="strike_outs" id="strike_outs" class="form-control" value=@if (old('strike_outs')) {{ old('strike_outs') }} @elseif (isset($player->strike_outs)) {{ $player->strike_outs }} @endif>
                        </div>
                    </div>
                    <div class="col">
                        <label for="whip" class="col-sm-8 control-label">WHIP</label>

                        <div class="col-sm-8">
                            <input type="text" name="whip" id="whip" class="form-control" value=@if (old('whip')) {{ old('whip') }} @elseif (isset($player->whip)) {{$player->whip}} @endif>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="previous_teams" class="col-sm-12 control-label">Previous Teams</label>

                    <div class="col-sm-12">
                        <input type="text" name="previous_teams" id="player->previous_teams" class="form-control" value=@if (old('previous_teams')) {{ old('previous_teams') }} @elseif (!empty($player->previous_teams)) {{implode(',', unserialize($player->previous_teams))}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="status" class="col-sm-12 control-label">Status</label>

                    <div class="col-sm-12">
                        <input type="text" name="status" id="player->status" class="form-control" value=@if (old('status')) {{ old('previous_teams') }} @elseif (!empty($player->status)) {{$player->status}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="photo" class="col-sm-12 control-label">Photo</label>

                    <div class="col-sm-12">
                        <input type="file" name="photo" id="player->photo" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group w-50 row">
                <div class="col">
                    <label for="minor_league_teams" class="col-sm-12 control-label">Minor League Teams</label>
                    <div class="col-sm-12">
                        <input type="text" name="minor_league_teams_search" id="minor_league_teams_search" class="form-control" value="@if(old('minor_league_teams_search')){{old('minor_league_teams_search')}}@elseif(!empty($minor_league_teams_search)){{$minor_league_teams_search}}@endif">
                    </div>
                    <div class="col"><div class="col-sm-8"><a id='mlt-link' href="#">show ids</a></div></div>
                    <div id="ids" class="col-sm-4">
                        <input type="text" name="minor_league_teams" id="minor_league_teams" class="form-control" value=@if (old('minor_league_teams')) {{ old('minor_league_teams') }} @elseif (!empty($player->minor_league_teams)) {{$player->minor_league_teams}} @endif>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                @if(!empty($player->id))
                    <div class="col-sm-offset-3 col-sm-6">
                        <input type="hidden" name="id" id="player-id" value="{{$player->id}}">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                @else
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-primary">Add Player</button>
                    </div>
                @endif
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="{{ asset('js/player.js') }}"></script>
@endsection