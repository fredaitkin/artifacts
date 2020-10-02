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
                        <th scope='col'>@sortablelink('last_name', 'Name')</th>
                        <th scope='col'>@sortablelink('team')</th>
                        <th scope='col'>@sortablelink('city')</th>
                        <th scope='col'>@sortablelink('state')</th>
                        <th scope='col'>@sortablelink('country')</th>
                        <th scope='col'>@sortablelink('birthdate', 'Age')</th>
                        <th scope='col'>@sortablelink('draftYear', 'Draft Yr')</th>
                        <th scope='col'>@sortablelink('draftRound', 'Draft Rnd')</th>
                        <th scope='col'>@sortablelink('draftPosition', 'Draft Pos')</th>
                        <th scope='col'>@sortablelink('debutYear', 'Debut Yr')</th>
                        <th scope='col'>@sortablelink('position', 'Position')</th>
                        <th scope='col'>@sortablelink('average', 'Avg')</th>
                        <th scope='col'>@sortablelink('atBats', 'At Bats')</th>
                        <th scope='col'>@sortablelink('homeRuns', 'HRs')</th>
                        <th scope='col'>@sortablelink('rbis', 'RBIs')</th>
                        <th scope='col'>@sortablelink('era')</th>
                        <th scope='col'>@sortablelink('games')</th>
                        <th scope='col'>@sortablelink('wins')</th>
                        <th scope='col'>@sortablelink('losses')</th>
                        <th scope='col'>@sortablelink('saves')</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($players as $player)
                            <tr>
                                <td class="table-text">
                                    {{ csrf_field() }}
                                    <a href="/player/{{ $player->id }}?view=true">{{ $player->last_name }},{{ substr($player->first_name, 0, 1) }}</a>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->team }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->city }}</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->state)) {{ $player->state }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->country }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->age }}</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->draft_year)) {{ $player->draft_year }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->draft_round)) {{ $player->draft_round }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->draft_position)) {{ $player->draft_position }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->debut_year }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $player->position }}</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->average)) {{ $player->average }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div class="text-right">@if (isset($player->at_bats)) {{ $player->at_bats }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div class="text-right">@if (isset($player->home_runs)) {{ $player->home_runs }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div class="text-right">@if (isset($player->rbis)) {{ $player->rbis }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div class="text-right">@if (isset($player->era)) {{ $player->era }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div class="text-right">@if (isset($player->games)) {{ $player->games }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->wins)) {{ $player->wins }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->losses)) {{ $player->losses }} @else -- @endif</div>
                                </td>
                                <td class="table-text">
                                    <div>@if (isset($player->saves)) {{ $player->saves }} @else -- @endif</div>
                                </td>
                                <td>
                                    {{ csrf_field() }}
                                    <a href="/player/{{ $player->id }}">edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(!empty($players))
                    {{ $players->appends(\Request::except('page'))->render() }}
                @endif
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