@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body artifacts-submit-form-div">

        <h2 class="col-sm-3">{{$title}}</h2>

        @include('common.errors')


        <!-- New player Form -->
        <form action="/player" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if(!empty($player->photo))
            <!-- player photo -->
            <div class="col-sm-6 form-group">
                <div class="col-sm-3">
                    <img src="{{ asset('storage/images/smalls/'.$player->photo) }}" alt="player_photo">
                </div>
            </div>
             @endif

            <div class="form-group">
                <label for="player" class="col-sm-3 control-label">First Name</label>

                <div class="col-sm-3">
                    <input type="text" name="first_name" id="player->first_name" class="form-control" @if( ! empty($player->first_name)) value="{{$player->first_name}}" @endif>
                </div>

            </div>

            <div class="form-group">
                <label for="last_name" class="col-sm-3 control-label">Last Name</label>

                <div class="col-sm-3">
                    <input type="text" name="last_name" id="player->last_name" class="form-control" @if( ! empty($player->last_name)) value="{{$player->last_name}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="team" class="col-sm-3 control-label">Team</label>

                <div class="col-sm-3">
                    <input type="text" name="team" id="player->team" class="form-control" @if( ! empty($player->team)) value="{{$player->team}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="city" class="col-sm-3 control-label">City</label>

                <div class="col-sm-4">
                    <input type="text" name="city" id="player->city" class="form-control" @if( ! empty($player->city)) value="{{$player->city}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="state" class="col-sm-3 control-label">State</label>

                <div class="col-sm-4">
                    <input type="text" name="state" id="player->state" class="form-control" @if( ! empty($player->state)) value="{{$player->state}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="country" class="col-sm-3 control-label">Country</label>
                <div class="col-sm-4">
                    <input type="text" name="country" id="player->country" class="form-control" @if( ! empty($player->country)) value="{{$player->country}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="birthdate" class="col-sm-3 control-label">Birth Date</label>

                <div class="col-sm-3">
                    <input type="text" name="birthdate" id="player->birthdate" class="form-control" @if( ! empty($player->birthdate)) value="{{$player->birthdate}}" @endif>
                </div>
            </div>
     
            <div class="form-group">
                <label for="draft_year" class="col-sm-3 control-label">Draft Year</label>

                <div class="col-sm-3">
                    <input type="text" name="draft_year" id="player->draft_year" class="form-control" @if( ! empty($player->draft_year)) value="{{$player->draft_year}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="draft_round" class="col-sm-3 control-label">Draft Round</label>

                <div class="col-sm-3">
                    <input type="text" name="draft_round" id="player->draft_round" class="form-control" @if( ! empty($player->draft_round)) value="{{$player->draft_round}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="draft_position" class="col-sm-3 control-label">Draft Position</label>

                <div class="col-sm-3">
                    <input type="text" name="draft_position" id="player->draft_position" class="form-control" @if( ! empty($player->draft_position)) value="{{$player->draft_position}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="debut_year" class="col-sm-3 control-label">Debut Year</label>

                <div class="col-sm-3">
                    <input type="text" name="debut_year" id="player->debut_year" class="form-control" @if( ! empty($player->debut_year)) value="{{$player->debut_year}}" @endif>
                </div>
            </div>

            <div class="form-group">
                <label for="photo" class="col-sm-3 control-label">Photo</label>

                <div class="col-sm-5">
                    <input type="file" name="photo" id="player->photo" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="previous_teams" class="col-sm-3 control-label">Previous Teams</label>

                <div class="col-sm-8">
                    <input type="text" name="previous_teams" id="player->previous_teams" class="form-control" @if( ! empty($player->previous_teams)) value="{{$player->previous_teams}}" @endif>
                </div>
            </div>

            <!-- Add player Button -->
            <div class="col-lg-6">
                @if( ! empty($player->id))
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
