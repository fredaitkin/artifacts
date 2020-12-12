@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Major League Teams</h2>

        <form class="form-inline" method="GET">
            <div class="form-group ml-5 mb-2">
                <label for="filter" class="control-label col-sm-4 col-form-label">Teams</label>
                <select class="form-control" name="filter">
                    <option value="all" @if ($filter == 'all') selected @endif>All</option>
                    <option value="current" @if ($filter == 'current') selected @endif>Current</option>
                </select>
            </div>
            <button type="submit" class="btn btn-default mb-2">Filter</button>
        </form>

        <table class="table table-striped artifacts-table">
            <thead>
                <tr>
                    <th scope='col'>@sortablelink('name')</th>
                    <th scope='col'>@sortablelink('league')</th>
                    <th scope='col'>@sortablelink('division')</th>
                    <th scope='col'>City</th>
                    <th scope='col'>@sortablelink('state')</th>
                    <th scope='col'>Ballpark</th>
                    <th scope='col'>@sortablelink('title_count', 'Titles')</th>
                    <th scope='col'>@sortablelink('founded')</th>
                    <th scope='col'>@sortablelink('closed', 'Defunct')</th>
                    <th scope='col'>Relocated To</th>
                    <th scope='col'>Relocated From</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teams as $team)
                    <tr>
                        <td class="table-text">
                             @if (Auth::user()->id === 1)
                                <a href="/team/{{ $team->team }}">{{ $team->name }}</a>
                            @else
                                {{ $team->name }}
                            @endif
                        </td>
                        <td class="table-text">{{ $team->league }}</td>
                        <td class="table-text">{{ $team->division }}</td>
                        <td class="table-text">{{ $team->city }}</td>
                        <td class="table-text">{{ $team->state }}</td>
                        <td class="table-text">{{ $team->ground }}</td>
                        <td class="table-text">{{ $team->title_count }}</td>
                        <td class="table-text">{{ $team->founded }}</td>
                        <td class="table-text">{{ $team->closed }}</td>
                        <td class="table-text">
                            @if (isset($team->relocated_to_display))
                                <a href="/team/{{ $team->relocated_to_display[0] }}">{{ $team->relocated_to_display[1] }}</a>
                            @endif
                        </td>
                        <td class="table-text">
                            @if (isset($team->relocated_from_display))
                                <a href="/team/{{ $team->relocated_from_display[0] }}">{{$team->relocated_from_display[1] }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $teams->appends(\Request::except('page'))->render() }}
        <a href="{{ url('/team') }}">Add</a>

    </div>

@endsection
