@extends('layouts.app')

@section('content')

    <div class="panel-body artifacts-submit-form-div">

        <h2 class="col-sm-4">{{$team->team}}</h2>

        @include('common.errors')

        <form action="/funfacts/mlt" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

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
                    <label for="affiliate" class="control-label">Affiliate</label>
                    <select class="form-control" name="affiliate">
                        @foreach($teams as $key => $major_league_team)
                            <option value="{{$key}}"  @if (old('affiliate') && old('affiliate') == $key) selected @elseif (!empty($team->affiliate) && ($team->affiliate == $key)) selected @endif>{{$major_league_team}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="form-group row">
                <div class="col-sm-4">
                    <label for="state" class="control-label">Class</label>
                    <select class="form-control" name="class">
                        @foreach($classes as $class)
                            <option value="{{$class}}"  @if (old('class') && old('class') == $class) selected @elseif (!empty($team->class) && ($team->class == $class)) selected @endif>{{$class}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="league" class="control-label">League</label>
                    <select class="form-control" name="league">
                        @foreach($leagues as $league)
                            <option value="{{$league}}"  @if (old('class') && old('league') == $class) selected @elseif (!empty($team->league) && ($team->league == $league)) selected @endif>{{$league}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="form-group row">
                <div class="col-sm-4">
                    <label for="division" class="control-label">Division</label>
                    <select class="form-control" name="division">
                        @foreach($divisions as $division)
                            <option value="{{$division}}" @if (old('division') && old('division') == $division) selected @elseif (!empty($team->division) && ($team->division == $division)) selected @endif>{{$division}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="country" class="control-label">Country</label>
                    <select class="form-control" name="country">
                        @foreach($countries as $country)
                            <option value="{{$country}}"  @if (old('country') && old('country') == $class) selected @elseif (!empty($team->country) && ($team->country == $country)) selected @endif>{{$country}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="form-group row">
                <div class="col-sm-4">
                    <input type="hidden" name="id" id="id" value="{{$team->id}}">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>

    </div>
@endsection