@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Statistics</h2>

        <ul class="nav nav-tabs" id="statsTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="batting-tab" data-toggle="tab" href="#batting" role="tab" aria-controls="batting"
              aria-selected="true">Batting</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pitching-tab" data-toggle="tab" href="#pitching" role="tab" aria-controls="pitching"
              aria-selected="false">Pitching</a>
            </li>
        </ul>

        <div class="tab-content" id="statsTabsContent">

            <div class="tab-pane fade show active" id="batting" role="tabpanel" aria-labelledby="gatting-tab">

                <div class="ml-3 mt-3 p-3 w-75 border row">
                    <div class="col">
                        <h6><strong>Most Home Runs</strong></h6>
                        <a href="/player/{{$most_home_runs->id}}?view=true">{{$most_home_runs->first_name}} {{$most_home_runs->last_name}}</a> {{config('teams')[$most_home_runs->team]}} <strong>{{$most_home_runs->home_runs}}</strong>
                    </div>
                    <div class="col">
                        <h6><strong>Most RBIs</strong></h6>
                        <a href="/player/{{$most_rbis->id}}?view=true">{{$most_rbis->first_name}} {{$most_rbis->last_name}}</a> {{config('teams')[$most_rbis->team]}} <strong>{{$most_rbis->rbis}}</strong>
                    </div>
                    <div class="col">
                        <h6><strong>Best Average</strong></h6>
                        <a href="/player/{{$best_average->id}}?view=true">{{$best_average->first_name}} {{$best_average->last_name}}</a> {{config('teams')[$best_average->team]}} <strong>{{$best_average->average}}</strong></br>
                        <small class="text-muted">* Batters with at least 500 at bats</small>
                    </div>
                </div>

                <div class="ml-3 mt-3 p-3 w-50 border row">
                    <div class="col">
                        <h6><strong>Best HR Strike Rate</strong></h6>
                        <a href="/player/{{$best_hr_strike_rate->id}}?view=true">{{$best_hr_strike_rate->first_name}} {{$best_hr_strike_rate->last_name}}</a> {{config('teams')[$best_hr_strike_rate->team]}} <strong>{{$best_hr_strike_rate->strike_rate}}</strong></br>
                        <small class="text-muted">* At bats per home run</small>
                    </div>
                    <div class="col">
                        <h6><strong>Best RBI Strike Rate</strong></h6>
                        <a href="/player/{{$best_rbi_strike_rate->id}}?view=true">{{$best_rbi_strike_rate->first_name}} {{$best_rbi_strike_rate->last_name}}</a> {{config('teams')[$best_rbi_strike_rate->team]}} <strong>{{$best_rbi_strike_rate->strike_rate}}</strong></br>
                        <small class="text-muted">* At bats per RBI</small>
                    </div>
                </div>

                <div class="ml-3 mt-3 mr-4 p-3 pr-4 border row">
                    <div class="col">
                        <h6><strong>Most Home Runs by Position</strong></h6>
                        @foreach($most_home_runs_by_position as $k => $v)
                             <div class="row">
                                <div class="col-sm-3">{{$k}}:</div>
                                <div class="col"><a href="/player/{{$v->id}}?view=true">{{$v->first_name}} {{$v->last_name}}</a></div>
                                <div class="col">{{config('teams')[$v->team]}}</div>
                                <div class="col-md-1"><span class="float-right"><strong>{{$v->home_runs}}</strong></span></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col">
                        <h6><strong>Most RBIs by Position</strong></h6>
                        @foreach($most_rbis_by_position as $k => $v)
                            <div class="row">
                                <div class="col-sm-3">{{$k}}:</div>
                                <div class="col"><a href="/player/{{$v->id}}?view=true">{{$v->first_name}} {{$v->last_name}}</a></div>
                                <div class="col">{{config('teams')[$v->team]}}</div>
                                <div class="col-md-1"><span class="float-right"><strong>{{$v->rbis}}</strong></span></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col">
                        <h6><strong>Best Average by Position</strong></h6>
                        @foreach($best_average_by_position as $k => $v)
                            <div class="row">
                                <div class="col-sm-3">{{$k}}:</div>
                                <div class="col"><a href="/player/{{$v->id}}?view=true">{{$v->first_name}} {{$v->last_name}}</a></div>
                                <div class="col">{{config('teams')[$v->team]}}</div>
                                <div class="col-md-1"><strong>{{$v->average}}</strong></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pitching" role="tabpanel" aria-labelledby="pitching-tab">

                <div class="ml-3 mt-3 p-3 w-75 border row">
                    <div class="col">
                        <h6><strong>Most Wins</strong></h6>
                        <a href="/player/{{$most_wins->id}}?view=true">{{$most_wins->first_name}} {{$most_wins->last_name}}</a> {{config('teams')[$most_wins->team]}} <strong>{{$most_wins->wins}}</strong>
                    </div>

                    <div class="col">
                        <h6><strong>Best ERA</strong></h6>
                        <a href="/player/{{$best_era->id}}?view=true">{{$best_era->first_name}} {{$best_era->last_name}}</a> {{config('teams')[$best_era->team]}} <strong>{{$best_era->era}}</strong></br>
                        <small class="text-muted">* Pitchers who've pitched at least 100 games and won at least 50 games</small>
                    </div>
                    <div class="col">
                        <h6><strong>Best Win Strike Rate</strong></h6>
                        <a href="/player/{{$best_win_strike_rate->id}}?view=true">{{$best_win_strike_rate->first_name}} {{$best_win_strike_rate->last_name}}</a> {{config('teams')[$best_win_strike_rate->team]}} <strong>{{$best_win_strike_rate->strike_rate}}</strong></br>
                        <small class="text-muted">* Games per win</small>
                    </div>
                </div>

            </div>

        </div>

    </div>


@endsection