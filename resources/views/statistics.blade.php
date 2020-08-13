@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Statistics</h2>

        <div class="ml-5 mt-4">
            <h5>Most home runs</h5>
            {{$most_home_runs->first_name}} {{$most_home_runs->last_name}} {{config('teams')[$most_home_runs->team]}} <strong>{{$most_home_runs->home_runs}}</strong>
        </div>

        <div class="ml-5 mt-4 w-50">
            <h5>Most home runs by position</h5>
            @foreach($most_home_runs_by_position as $k => $v)
                 <div class="row">
                    <div class="col">{{$k}}:</div>
                    <div class="col">{{$v->first_name}} {{$v->last_name}}</div>
                    <div class="col">{{config('teams')[$v->team]}}</div>
                    <div class="col"><strong>{{$v->home_runs}}</strong></div>
                </div>
            @endforeach
        </div>

        <div class="ml-5 mt-4">
            <h5>Most RBIs</h5>
            {{$most_rbis->first_name}} {{$most_rbis->last_name}} {{config('teams')[$most_rbis->team]}} <strong>{{$most_rbis->rbis}}</strong>
        </div>

        <div class="ml-5 mt-4 w-50">
            <h5>Most RBIs by position</h5>
            @foreach($most_rbis_by_position as $k => $v)
                 <div class="row">
                    <div class="col">{{$k}}:</div>
                    <div class="col">{{$v->first_name}} {{$v->last_name}}</div>
                    <div class="col">{{config('teams')[$v->team]}}</div>
                    <div class="col"><strong>{{$v->rbis}}</strong></div>
                </div>
            @endforeach
        </div>

        <div class="ml-5 mt-4">
            <h5>Most wins</h5>
            {{$most_wins->first_name}} {{$most_wins->last_name}} {{config('teams')[$most_wins->team]}} <strong>{{$most_wins->wins}}</strong>
        </div>

        <div class="ml-5 mt-4">
            <h5>Best ERA*</h5>
            {{$best_era->first_name}} {{$best_era->last_name}} {{config('teams')[$best_era->team]}} <strong>{{$best_era->era}}</strong>
            <div><small class="text-muted">* Pitchers who've pitched at least 100 games</small></div>
        </div>
    </div>

@endsection