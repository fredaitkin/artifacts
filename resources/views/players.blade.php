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
            <h3>Current Players</h3>
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
                        <th scope='col'>@sortablelink('draft_year', 'Draft Year')</th>
                        <th scope='col'>@sortablelink('draft_round', 'Draft Round')</th>
                        <th scope='col'>@sortablelink('draft_position', 'Draft Position')</th>
                        <th scope='col'>@sortablelink('debut_year', 'Debut Year')</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($players as $player)
                            <tr>
                                <td class="table-text">
                                    <div>{{ $player->first_name }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->last_name }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->team }}</div>
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
                                <td>
                                    {{ csrf_field() }}
                                    <a href="/player/{{ $player->id }}">edit</a>
                                </td>
                                <td>
                                    <form action="/player/{{ $player->id }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                         <a href="javascript:;" onclick="parentNode.submit();">delete</a>
                                    </form>
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