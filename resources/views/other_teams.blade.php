@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h5 class="col-sm-3">Other Teams</h5>

        <form action="/other-teams" role="search">
            {{ csrf_field() }}
            <div class="input-group col-sm-6">
                <input type="text" class="form-control" name="q"
                    placeholder="Search teams" value="@if (isset($q)){{ $q }} @endif"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">
                        <span class="glyphicon glyphicon-search">Search</span>
                    </button>
                </span>
                @if (Auth::user()->id === 1)
                    <a href="{{ url('/other-team') }}" class="btn btn-xs btn-info pull-right">Add</a>
                @endif
            </div>
        </form>

        <table class="table table-striped artifacts-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th scope='col'>@sortablelink('team')</th>
                    <th scope='col'>@sortablelink('league')</th>
                    <th scope='col'>@sortablelink('founded')</th>
                    <th scope='col'>City</th>
                    <th scope='col'>@sortablelink('country')</th>
                    <th scope='col'>Other Names</th>
                    <th scope='col'>@sortablelink('player_count', 'Players')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teams as $team)
                    <tr>
                        <td><img src="{{ asset('storage/other_teams/smalls/' . $team->logo) }}" alt="team_photo"></td>
                        <td class="table-text">
                             @if (Auth::user()->id === 1)
                                <a href="/other-team/{{ $team->id }}">{{ $team->name }}</a>
                            @else
                                {{ $team->name }}
                            @endif
                        </td>
                        <td class="table-text">{{ $team->league }}</td>
                        <td class="table-text">{{ $team->founded }}</td>
                        <td class="table-text">{{ $team->city }}</td>
                        <td class="table-text">{{ $team->country }}</td>
                        <td class="table-text">{{ $team->other_names }}</td>
                        <td class="table-text">{{ $team->player_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $teams->appends(\Request::except('page'))->render() }}
        <a href="{{ url('/other-team') }}">Add</a>

    </div>

@endsection
