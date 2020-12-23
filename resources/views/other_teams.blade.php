@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h5 class="col-sm-3">Other Teams</h5>

        <table class="table table-striped artifacts-table">
            <thead>
                <tr>
                    <th scope='col'>@sortablelink('team')</th>
                    <th scope='col'>@sortablelink('league')</th>
                    <th scope='col'>@sortablelink('founded')</th>
                    <th scope='col'>City</th>
                    <th scope='col'>@sortablelink('country')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teams as $team)
                    <tr>
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
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $teams->appends(\Request::except('page'))->render() }}
        <a href="{{ url('/other-team') }}">Add</a>

    </div>

@endsection
