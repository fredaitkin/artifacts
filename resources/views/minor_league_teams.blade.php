@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Minor League Teams</h2>

                <table class="table table-striped artifacts-table">
                    <thead>
                        <tr>
                            <th scope='col'>@sortablelink('team')</th>
                            <th scope='col'>@sortablelink('class')</th>
                            <th scope='col'>@sortablelink('affiliate')</th>
                            <th scope='col'>@sortablelink('league')</th>
                            <th scope='col'>City</th>
                            <th scope='col'>@sortablelink('state')</th>
                            <th scope='col'>@sortablelink('country')</th>
                            <th scope='col'>@sortablelink('founded')</th>
                            <th scope='col'>@sortablelink('player_count', 'Players')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ml_teams as $ml_team)
                            <tr>
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
                                <td class="table-text">{{ $ml_team->player_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $ml_teams->appends(\Request::except('page'))->render() }}
                <a href="{{ url('/minor-league-team') }}">Add</a>

            </div>

        </div>

    </div>

@endsection
