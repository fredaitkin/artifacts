@extends('layouts.app')

@section('content')

    <div class="panel-body artifacts-submit-form-div">

        <h2 class="col-sm-3">{{$player->first_name}} {{$player->last_name}}</h2>

        <form>
            @if(!empty($player->photo))
                <div>
                    <img src="{{ asset('storage/images/smalls/'.$player->photo) }}" alt="player_photo">
                </div>
            @endif
 
            <div>
                <div>
                    {{$player->team_display}}
                </div>
                <div>
                    {{$player->city}},         
                    @if(!empty($player->state)) 
                        {{$player->state}},  
                    @endif 
                    {{$player->country}}
                </div>
                <div>
                    {{$player->birthdate}}
                </div>
                <div>
                    @if(!empty($player->draft_year))
                        Drafted in {{$player->draft_year}}
                    @endif 
                    @if(!empty($player->draft_round))
                        , round {{$player->draft_round}}
                    @endif
                    @if(!empty($player->draft_position))
                        , position {{$player->draft_position}}
                    @endif
                </div>
                <div>
                    Debuted in {{$player->debut_year}}
                </div>
                <div>
                    {{$player->previous_teams}}
                </div>
            </div>
        </form>

    </div>
@endsection
