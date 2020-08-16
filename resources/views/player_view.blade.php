@extends('layouts.app')

@section('content')

    <div class="panel-body row content team-div {{$player->team}}-div">

        <h2 class="col-sm-3">{{$player->first_name}} {{$player->last_name}}</h2>

        <form>
            @if(!empty($player->photo))
                <div>
                    <img class="img-thumbnail" src="{{asset('storage/images/regular/'.$player->regular_photo)}}" alt="player_photo">
                </div>
            @endif
 
            <div class="mt-4 font-weight-bold">

                <h4>{{$player->team_display}}</h4>

                <div class="mt-2">
                    @if(!empty($player->draft_year))
                        Drafted: {{$player->draft_year}}</br>
                    @endif 
                    @if(!empty($player->draft_round))
                        Round: {{$player->draft_round}}</br>
                    @endif
                    @if(!empty($player->draft_position))
                        Position: {{$player->draft_position}}</br>
                    @endif
                    Debuted: {{$player->debut_year}}
                </div>

                <div class="mt-2">
                    @if(!empty($player->position))
                        Position: {{$player->position}}</br>
                        @if($player->position == 'P')
                            ERA: {{$player->era}}</br>
                            Games: {{$player->games}}</br>
                            Wins: {{$player->wins}}</br>
                        @endif
                        @if(!empty($player->average))
                            AVG: {{$player->average}}</br>
                            ABs: {{$player->at_bats}}</br>
                            HRs: {{$player->home_runs}}</br>
                            RBIs: {{$player->rbis}}</br>
                        @endif
                    @endif
                </div>

                <div class="mt-2">
                    Origin: {{$player->city}},         
                    @if(!empty($player->state)) 
                        {{$player->state}},  
                    @endif 
                    {{$player->country}}</br>
                    Age: {{$player->age}}</br>
                    Birthdate: {{$player->birth_date_display}}
                </div>

                <div class="mt-2 previous-teams-div">
                    @if(!empty($player->previous_teams))
                        Previous teams: {{$player->previous_teams_display}}
                    @endif
                </div>
            </div>
        </form>

    </div>
@endsection
