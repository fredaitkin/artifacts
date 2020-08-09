@extends('layouts.app')

@section('content')

    <div class="panel-body">

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                Please fix the following errors
            </div>
        @endif

        @if (isset($message))
            <p>{{ $message }}</p>
        @endif

        <div class="col-sm-3">
            <h5>Current Players</h5>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <form action="/players/search" method="POST" role="search">
                    {{ csrf_field() }}
                    <div class="input-group col-sm-6">
                        <input type="text" class="form-control" name="q"
                            placeholder="Search players"> <span class="input-group-btn">
                            <button type="submit" class="btn btn-default">
                                <span class="glyphicon glyphicon-search">Search</span>
                            </button>
                        </span>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/player') }}" class="btn btn-xs btn-info pull-right">Add</a>
                            @endauth
                        @endif
                    </div>
                </form>
            </div>

            <div class="panel-body">
                <table class="table table-striped artifacts-table">

                    <thead>
                        <tr>
                        <th scope='col'>@sortablelink('first_name', 'First Name')</th>
                        <th scope='col'>@sortablelink('last_name', 'Last Name')</th>
                        <th scope='col'>@sortablelink('team')</th>
                        <th scope='col'>@sortablelink('city')</th>
                        <th scope='col'>@sortablelink('state')</th>
                        <th scope='col'>@sortablelink('country')</th>
                        <th scope='col'>@sortablelink('birthdate')</th>
                        <th scope='col'>@sortablelink('draftYear', 'Draft Year')</th>
                        <th scope='col'>@sortablelink('draftRound', 'Draft Round')</th>
                        <th scope='col'>@sortablelink('draftPosition', 'Draft Position')</th>
                        <th scope='col'>@sortablelink('debutYear', 'Debut Year')</th>
                        <th scope='col'>@sortablelink('position', 'Position')</th>
                        <th scope='col'>@sortablelink('average', 'Avg')</th>
                        <th scope='col'>@sortablelink('homeRuns', 'HRs')</th>
                        <th scope='col'>@sortablelink('era', 'Era')</th>
                        <th scope='col'>@sortablelink('wins', 'Wins')</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($players as $player)
                            <tr>
                                <td class="table-text">
                                    {{ csrf_field() }}
                                    <a href="/player/{{ $player->id }}?view=true">{{ $player->first_name }}</a>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->last_name }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->team_display }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->city }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->state }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->country }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->birthdate }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->draft_year }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->draft_round }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->draft_position }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->debut_year }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->position }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->average }}</div>
                                </td>
                                <td class="table-text">
                                    <div class="text-right">{{ $player->home_runs }}</div>
                                </td>
                                <td class="table-text">
                                    <div class="text-right">{{ $player->era }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->wins }}</div>
                                </td>
                                <td>
                                    {{ csrf_field() }}
                                    <a href="/player/{{ $player->id }}">edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $players->appends(\Request::except('page'))->render() }}

            </div>
        </div>

        <div>
            @if (Route::has('login'))
                <div class="col-sm-3">
                    @auth
                        <a href="{{ url('/player') }}">Add</a>
                    @endauth
                </div>
            @endif
        </div>


@endsection