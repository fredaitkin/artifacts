@extends('layouts.app')

@section('content')

    <div class="panel-body content font-weight-bold team-div {{$player->team}}-div">

        <div class="row">

            <h2 class="col-sm-3">{{$player->first_name}} {{$player->last_name}}</h2>

            @if(!empty($player->photo))
                <div>
                    <img class="img-thumbnail" src="{{asset('storage/images/regular/'.$player->regular_photo)}}" alt="player_photo">
                </div>
            @endif
 
            <div class="mt-5 ml-5">
                {{$player->city}},
                @if(!empty($player->state))
                    {{$player->state_display}}
                @else
                    {{$player->country}}
                @endif
                </br>
                {{$player->age}}</br>
                Born {{$player->birth_date_display}}</br></br>
                 @if(!empty($player->draft_position && $player->draft_position == 1 ))
                    Number One Draft Pick!
                 @elseif(!empty($player->draft_round && $player->draft_round == 1 ))
                    First Round Draft Pick!
                 @endif
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>
            <div class="mt-2">
                <h4>{{$player->team_display}}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>
            <div class="mt-2">
                {{$player->position_display}}
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>
            <div class="mt-2 w-25">
                @if(!empty($player->draft_year))
                    <div class="row">
                        <div class="col-sm-5">Drafted:</div>
                        <div class="col-sm-3 text-right">{{$player->draft_year}}</div>
                    </div>
                @endif
                @if(!empty($player->draft_round))
                    <div class="row">
                        <div class="col-sm-5">Round:</div>
                       <div class="col-sm-3 text-right">{{$player->draft_round}}</div>
                    </div>
                @endif
                @if(!empty($player->draft_position))
                    <div class="row">
                        <div class="col-sm-5">Position:</div>
                        <div class="col-sm-3 text-right">{{$player->draft_position}}</div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-5">Debuted:</div>
                    <div class="col-sm-3 text-right">{{$player->debut_year}}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>
            <div class="mt-2 w-25">
                @if(!empty($player->position))
                    @if($player->position == 'P')
                        <div class="row">
                            <div class="col-sm-5">ERA:</div>
                            <div class="col-sm-3 text-right">{{$player->era}}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">Games:</div>
                            <div class="col-sm-3 text-right">{{$player->games}}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">Wins:</div>
                            <div class="col-sm-3 text-right">{{$player->wins}}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">Losses:</div>
                            <div class="col-sm-3 text-right">{{$player->losses}}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">Saves:</div>
                            <div class="col-sm-3 text-right">{{$player->saves}}</div>
                        </div>
                    @endif
                    @if(!empty($player->average))
                        <div class="row">
                            <div class="col-sm-5">Average:</div>
                            <div class="col-sm-3 text-right">{{$player->average}}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">At Bats:</div>
                            <div class="col-sm-3 text-right">{{$player->at_bats}}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">Home Runs:</div>
                            <div class="col-sm-3 text-right">{{$player->home_runs}}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">RBIs:</div>
                            <div class="col-sm-3 text-right">{{$player->rbis}}</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>
            <div class="mt-2 previous-teams-div">
                @if(!empty($player->previous_teams))
                    Previous teams: {{$player->previous_teams_display}}
                @endif
            </div>
        </div>

    </div>
@endsection