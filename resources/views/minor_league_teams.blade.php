@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h5 class="col-sm-3">Minor League Teams</h5>

        <form action="/minor-league-teams" role="search">
            {{ csrf_field() }}
            <div class="input-group col-sm-6">
                <input type="text" class="form-control" name="q"
                    placeholder="Search teams" value="@if (isset($q)){{ $q }} @endif"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">
                        <span class="glyphicon glyphicon-search">Search</span>
                    </button>
                </span>
                @if (Auth::user()->id === 1)
                    <a href="{{ url('/minor-league-team') }}" class="btn btn-xs btn-info pull-right">Add</a>
                @endif
            </div>
        </form>

        <table class="table table-striped artifacts-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th scope='col'>@sortablelink('team')</th>
                    <th scope='col'>@sortablelink('class')</th>
                    <th scope='col'>@sortablelink('affiliate')</th>
                    <th scope='col'>@sortablelink('league')</th>
                    <th scope='col'>City</th>
                    <th scope='col'>@sortablelink('state')</th>
                    <th scope='col'>@sortablelink('country')</th>
                    <th scope='col'>@sortablelink('founded')</th>
                    <th scope='col'>Other Names</th>
                    <th scope='col'>@sortablelink('player_count', 'Players')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ml_teams as $ml_team)
                    <tr>
                        <td><img class="img-thumbnail" src="{{ asset('storage/minor_league_teams/smalls/' . $ml_team->id . '.png') }}" alt="team_photo"></td>
                        <td class="table-text">
                             @if (Auth::user()->id === 1)
                                <a href="/minor-league-team/{{ $ml_team->id }}">{{ $ml_team->team }}</a>
                            @else
                                {{ $ml_team->team }}
                            @endif
                        </td>
                        <td class="table-text">{{ $ml_team->class }}</td>
                        <td class="table-text">{{ $ml_team->affiliate }}</td>
                        <td class="table-text">{{ $ml_team->league }}</td>
                        <td class="table-text">{{ $ml_team->city }}</td>
                        <td class="table-text">{{ $ml_team->state }}</td>
                        <td class="table-text">{{ $ml_team->country }}</td>
                        <td class="table-text">{{ $ml_team->founded }}</td>
                        <td class="table-text">{{ $ml_team->other_names }}</td>
                        <td class="table-text">{{ $ml_team->player_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $ml_teams->appends(\Request::except('page'))->render() }}
        <a href="{{ url('/minor-league-team') }}">Add</a>

    </div>

@endsection
