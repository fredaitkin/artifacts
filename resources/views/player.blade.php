@extends('layouts.app')

@section('content')

    <div class="panel-body artifacts-submit-form-div @if(!empty($player->team)) {{$player->team}}-div @endif">

        <h2 class="col-sm-8">{{$title}}</h2>

        @include('common.errors')

        <form action="/player" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="form-group w-50 row">
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
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="team" class="col-sm-8 control-label">Team</label>

                    <div class="col-sm-8">

                        <select class="form-control" name="team">
                            @foreach($teams as $key => $team)
                                <option value="{{$key}}"  @if (old('team') && old('team') == $key) selected @elseif (!empty($player->team) && ($player->team == $key)) selected @endif>{{$team}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
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
            </div>

            <div class="form-group row">
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
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="draft_round" class="col-sm-8 control-label">Draft Round</label>

                    <div class="col-sm-8">
                        <input type="text" name="draft_round" id="draft_round" class="form-control" value=@if (old('draft_round')) {{ old('draft_round') }} @elseif (!empty($player->draft_round)) {{$player->draft_round}} @endif>
                    </div>
                </div>
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
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="position" class="col-sm-8 control-label">Position</label>

                    <div class="col-sm-8">

                        <select class="form-control" name="position">
                            @foreach($positions as $key => $position)
                                <option value="{{$key}}" @if (old('position') && old('position') == $key) selected @elseif (!empty($player->position) && ($player->position == $key)) selected @endif>{{$position}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
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
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="home_runs" class="col-sm-8 control-label">Home Runs</label>

                    <div class="col-sm-8">
                        <input type="text" name="home_runs" id="home_runs" class="form-control" value=@if (old('home_runs')) {{ old('home_runs') }} @elseif (isset($player->home_runs)) {{$player->home_runs}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="rbis" class="col-sm-8 control-label">RBIs</label>

                    <div class="col-sm-8">
                        <input type="text" name="rbis" id="rbis" class="form-control" value=@if (old('rbis')) {{ old('rbis') }} @elseif (isset($player->rbis)) {{$player->rbis}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="era" class="col-sm-8 control-label">ERA</label>

                    <div class="col-sm-8">
                        <input type="text" name="era" id="era" class="form-control" value=@if (old('era')) {{ old('era') }} @elseif (isset($player->era)) {{$player->era}} @endif>
                    </div>
                </div>
            </div>

           <div class="form-group row">
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
                <div class="col">
                    <label for="losses" class="col-sm-8 control-label">Losses</label>

                    <div class="col-sm-8">
                        <input type="text" name="losses" id="player->losses" class="form-control" value=@if (old('losses')) {{ old('losses') }} @elseif (isset($player->losses)) {{$player->losses}} @endif>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="saves" class="col-sm-8 control-label">Saves</label>

                    <div class="col-sm-8">
                        <input type="text" name="saves" id="player->saves" class="form-control" value=@if (old('saves')) {{ old('saves') }} @elseif (isset($player->saves)) {{ $player->saves }} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="previous_teams" class="col-sm-12 control-label">Previous Teams</label>

                    <div class="col-sm-12">
                        <input type="text" name="previous_teams" id="player->previous_teams" class="form-control" value=@if (old('previous_teams')) {{ old('previous_teams') }} @elseif (!empty($player->previous_teams)) {{$player->previous_teams}} @endif>
                    </div>
                </div>
                <div class="col">
                    <label for="photo" class="col-sm-12 control-label">Photo</label>

                    <div class="col-sm-12">
                        <input type="file" name="photo" id="player->photo" class="form-control">
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
    <script src="{{ asset('js/player.js') }}"></script>
@endsection