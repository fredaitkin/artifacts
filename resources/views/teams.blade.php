@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Minor League Teams</h2>

                <table class="table table-striped artifacts-table">
                    <thead>
                        <tr>
                            <th scope='col'>@sortablelink('name')</th>
                            <th scope='col'>City</th>
                            <th scope='col'>@sortablelink('state')</th>
                            <th scope='col'>@sortablelink('ground')</th>
                            <th scope='col'>@sortablelink('founded')</th>
                            <th scope='col'>@sortablelink('closed', 'Defunct')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                            <tr>
                                <td class="table-text">
                                     @if(Auth::user()->id === 1)
                                        <a href="/team/{{$team->team}}">{{$team->name}}</a>
                                    @else
                                        {{$team->name}}
                                    @endif
                                </td>
                                <td class="table-text">{{$team->city}}</td>
                                <td class="table-text">{{$team->state}}</td>
                                <td class="table-text">{{$team->ground}}</td>
                                <td class="table-text">{{$team->founded}}</td>
                                <td class="table-text">{{$team->closed}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $teams->appends(\Request::except('page'))->render() }}
                <a href="{{ url('/team') }}">Add</a>

            </div>

        </div>

    </div>

@endsection