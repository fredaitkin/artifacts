@extends('layouts.app')

@section('content')

    <div class="panel-body artifacts-submit-form-div">

        <h2 class="col-sm-4">@if(!empty($team->name)) {{$team->name}} @else Add Team @endif</h2>

        @include('common.errors')

        <form action="/team" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if(empty($team->team))
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="team" class="control-label">Team</label>
                        <input type="text" name="team" class="form-control" value="@if (old('team')){{old('team')}}@elseif (!empty($team->team)){{$team->team}}@endif">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" class="form-control" value="@if (old('name')){{old('name')}}@elseif (!empty($team->name)){{$team->name}}@endif">
                    </div>
                </div>
            @else
                <input type="hidden" name="team" class="form-control" value="{{$team->team}}">
                <input type="hidden" name="name" class="form-control" value="{{$team->name}}">
            @endif

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="city" class="control-label">City</label>
                    <input type="text" name="city" class="form-control" value="@if (old('city')){{old('city')}}@elseif (!empty($team->city)){{$team->city}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="state" class="control-label">State</label>
                    <select class="form-control" name="state">
                        @foreach($states as $key => $state)
                            <option value="{{$key}}"  @if (old('state') && old('state') == $key) selected @elseif (!empty($team->state) && ($team->state == $key)) selected @endif>{{$state}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="country" class="control-label">Country</label>
                    <input type="text" name="country" class="form-control" value="@if (old('country')){{old('country')}}@elseif (!empty($team->country)){{$team->country}}@else{{'US'}} @endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="league" class="control-label">League</label>
                    <select class="form-control" name="league">
                        @foreach($leagues as $key => $league)
                            <option value="{{$key}}"  @if (old('league') && old('league') == $key) selected @elseif (!empty($team->league) && ($team->league == $key)) selected @endif>{{$league}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="division" class="control-label">Division</label>
                    <select class="form-control" name="division">
                        @foreach($divisions as $division)
                            <option value="{{$division}}"  @if (old('division') && old('division') == $division) selected @elseif (!empty($team->division) && ($team->division == $division)) selected @endif>{{$division}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ground" class="control-label">Ballpark</label>
                    <input type="text" name="ground" class="form-control" value="@if (old('ground')){{old('ground')}}@elseif (!empty($team->ground)){{$team->ground}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="founded" class="control-label">Founded</label>
                    <input type="text" name="founded" class="form-control" value="@if (old('founded')){{old('founded')}}@elseif (!empty($team->founded)){{$team->founded}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="closed" class="control-label">Defunct</label>
                    <input type="text" name="closed" class="form-control" value="@if (old('closed')){{old('closed')}}@elseif (!empty($team->closed)){{$team->closed}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="titles" class="control-label">Titles</label>
                    <input type="text" name="titles" class="form-control" value="@if (old('titles')){{old('titles')}}@elseif (!empty($team->titles)){{$team->titles_display}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="logo" class="control-label">Logo</label>
                    <input type="text" name="logo" class="form-control" value="@if (old('logo')){{old('logo')}}@elseif (!empty($team->logo)){{$team->logo}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="other_names" class="control-label">Other Names</label>
                    <input type="text" name="other_names" class="form-control" value="@if (old('other_names')){{old('other_names')}}@elseif (!empty($team->other_names)){{$team->other_names}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="relocated_to" class="control-label">Relocated To</label>
                    <input type="text" name="relocated_to" class="form-control" value="@if (old('relocated_to')){{old('relocated_to')}}@elseif (!empty($team->relocated_to)){{$team->relocated_to}}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="relocated_from" class="control-label">Relocated From</label>
                    <input type="text" name="relocated_from" class="form-control" value="@if (old('relocated_from')){{old('relocated_from')}}@elseif (!empty($team->relocated_from)){{$team->relocated_from}}@endif">
                </div>
            </div>

             <div class="form-group row">
                <div class="col-sm-4">
                    @if(!empty($team->team))
                        <div class="col-sm-offset-3 col-sm-6">
                            <input type="hidden" name="id" id="id" value="{{$team->team}}">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    @else
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-primary">Add Team</button>
                        </div>
                    @endif
                </div>
            </div>
        </form>

    </div>
@endsection