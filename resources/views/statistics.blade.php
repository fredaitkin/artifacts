@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Statistics</h2>

        <div class="ml-3 mt-4 w-75 row">
            <h4><em>Batting</em></h3>
        </div>

        <div class="ml-3 mt-3 p-3 w-75 border row">
            <div class="col">
                <h6><strong>Most Home Runs</strong></h6>
                {{$most_home_runs->first_name}} {{$most_home_runs->last_name}} {{config('teams')[$most_home_runs->team]}} <strong>{{$most_home_runs->home_runs}}</strong>
            </div>
            <div class="col">
                <h6><strong>Most RBIs</strong></h6>
                {{$most_rbis->first_name}} {{$most_rbis->last_name}} {{config('teams')[$most_rbis->team]}} <strong>{{$most_rbis->rbis}}</strong>
            </div>
            <div class="col">
                <h6><strong>Best Average</strong></h6>
                {{$best_average->first_name}} {{$best_average->last_name}} {{config('teams')[$best_average->team]}} <strong>{{$best_average->average}}</strong></br>
                <small class="text-muted">* Batters with at least 500 at bats</small>
            </div>
        </div>

        <div class="ml-3 mt-3 p-3 w-50 border row">
            <div class="col">
                <h6><strong>Best HR Strike Rate</strong></h6>
                {{$best_hr_strike_rate->first_name}} {{$best_hr_strike_rate->last_name}} {{config('teams')[$best_hr_strike_rate->team]}} <strong>{{$best_hr_strike_rate->strike_rate}}</strong></br>
                <small class="text-muted">* At bats per home run</small>
            </div>
            <div class="col">
                <h6><strong>Best RBI Strike Rate</strong></h6>
                {{$best_rbi_strike_rate->first_name}} {{$best_rbi_strike_rate->last_name}} {{config('teams')[$best_rbi_strike_rate->team]}} <strong>{{$best_rbi_strike_rate->strike_rate}}</strong></br>
                <small class="text-muted">* At bats per RBI</small>
            </div>
        </div>
        <div class="ml-3 mt-3 mr-4 p-3 pr-4 border row">
            <div class="col">
                <h6><strong>Most Home Runs by Position</strong></h6>
                @foreach($most_home_runs_by_position as $k => $v)
                     <div class="row">
                        <div class="col">{{$k}}:</div>
                        <div class="col">{{$v->first_name}} {{$v->last_name}}</div>
                        <div class="col">{{config('teams')[$v->team]}}</div>
                        <div class="col-md-1"><span class="float-right"><strong>{{$v->home_runs}}</strong></span></div>
                    </div>
                @endforeach
            </div>
            <div class="col">
                <h6><strong>Most RBIs by Position</strong></h6>
                @foreach($most_rbis_by_position as $k => $v)
                    <div class="row">
                        <div class="col">{{$k}}:</div>
                        <div class="col">{{$v->first_name}} {{$v->last_name}}</div>
                        <div class="col">{{config('teams')[$v->team]}}</div>
                        <div class="col-md-1"><span class="float-right"><strong>{{$v->rbis}}</strong></span></div>
                    </div>
                @endforeach
            </div>
            <div class="col">
                <h6><strong>Best Average by Position</strong></h6>
                @foreach($best_average_by_position as $k => $v)
                    <div class="row">
                        <div class="col">{{$k}}:</div>
                        <div class="col">{{$v->first_name}} {{$v->last_name}}</div>
                        <div class="col">{{config('teams')[$v->team]}}</div>
                        <div class="col-md-1"><strong>{{$v->average}}</strong></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ml-3 mt-4 w-75 row">
            <h4><em>Pitching</em></h3>
        </div>

        <div class="ml-3 mt-3 p-3 w-75 border row">
            <div class="col">
                <h6><strong>Most Wins</strong></h6>
                {{$most_wins->first_name}} {{$most_wins->last_name}} {{config('teams')[$most_wins->team]}} <strong>{{$most_wins->wins}}</strong>
            </div>

            <div class="col">
                <h6><strong>Best ERA</strong></h6>
                {{$best_era->first_name}} {{$best_era->last_name}} {{config('teams')[$best_era->team]}} <strong>{{$best_era->era}}</strong></br>
                <small class="text-muted">* Pitchers who've pitched at least 100 games and won at least 50 games</small>
            </div>
            <div class="col">
                <h6><strong>Best Win Strike Rate</strong></h6>
                {{$best_win_strike_rate->first_name}} {{$best_win_strike_rate->last_name}} {{config('teams')[$best_win_strike_rate->team]}} <strong>{{$best_win_strike_rate->strike_rate}}</strong></br>
                <small class="text-muted">* Games per win</small>
            </div>
        </div>
    </div>

@endsection