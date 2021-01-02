@extends('layouts.app')

@section('content')

    <div class="panel-body artifacts-submit-form-div">

        @if (!empty($team->team))
            <h2 class="col-sm-4">{{ $team->team }}</h2>
            <img class="img-thumbnail mb-2" src="{{ asset('storage/minor_league_teams/regular/' . $team->logo) }}" alt="team_photo">
        @else
            <h2 class="col-sm-4">Add Team</h2>
        @endif

        @include('common.errors')

        <form action="/minor-league-team" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if (empty($team->id))
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="team" class="control-label">Team</label>
                        <input type="text" name="team" class="form-control" value="@if (old('team')){{ old('team') }}@elseif (!empty($team->team)){{ $team->team }}@endif">
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="city" class="control-label">City</label>
                    <input type="text" name="city" class="form-control" value="@if (old('city')){{ old('city') }}@elseif (!empty($team->city)){{ $team->city }}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="state" class="control-label">State</label>
                    <select class="form-control" name="state">
                        @foreach ($states as $key => $state)
                            <option value="{{ $key }}"  @if (old('state') && old('state') == $key) selected @elseif (!empty($team->state) && ($team->state == $key)) selected @endif>{{ $state }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="affiliate" class="control-label">Affiliate</label>
                    <select class="form-control" name="affiliate">
                        @foreach ($teams as $key => $major_league_team)
                            <option value="{{ $key }}"  @if (old('affiliate') && old('affiliate') == $key) selected @elseif (!empty($team->affiliate) && ($team->affiliate == $key)) selected @endif>{{ $major_league_team }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="form-group row">
                <div class="col-sm-4">
                    <label for="state" class="control-label">Class</label>
                    <select class="form-control" name="class">
                        @foreach ($classes as $class)
                            <option value="{{ $class }}"  @if (old('class') && old('class') == $class) selected @elseif (!empty($team->class) && ($team->class == $class)) selected @endif>{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="league" class="control-label">League</label>
                    <select class="form-control" name="league">
                        @foreach ($leagues as $league)
                            <option value="{{ $league }}"  @if (old('league') && old('league') == $league) selected @elseif (!empty($team->league) && ($team->league == $league)) selected @endif>{{ $league }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="form-group row">
                <div class="col-sm-4">
                    <label for="division" class="control-label">Division</label>
                    <select class="form-control" name="division">
                        @foreach ($divisions as $division)
                            <option value="{{ $division }}" @if (old('division') && old('division') == $division) selected @elseif (!empty($team->division) && ($team->division == $division)) selected @endif>{{ $division }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="founded" class="control-label">Founded</label>
                    <input type="text" name="founded" class="form-control" value="@if (old('founded')){{ old('founded') }}@elseif (!empty($team->founded)){{ $team->founded }}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="defunct" class="control-label">Defunct</label>
                    <input type="text" name="defunct" class="form-control" value="@if (old('defunct')){{ old('defunct') }}@elseif (!empty($team->defunct)){{ $team->defunct }}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="previous_teams" class="control-label">Previous Affiliations</label>
                    <input type="text" name="previous_teams" class="form-control" value="@if (old('previous_teams')){{ old('previous_teams') }}@elseif (!empty($team->previous_teams)){{  implode(',', unserialize($team->previous_teams))  }}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="other_names" class="control-label">Other Names</label>
                    <input type="text" name="other_names" class="form-control" value="@if (old('other_names')){{ old('other_names') }}@elseif (!empty($team->other_names)){{ $team->other_names }}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="country" class="control-label">Country</label>
                    <select class="form-control" name="country">
                        @foreach ($countries as $country)
                            <option value="{{ $country }}"  @if (old('country') && old('country') == $class) selected @elseif (!empty($team->country) && ($team->country == $country)) selected @endif>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="logo" class="control-label">Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control">
                </div>
            </div>

             <div class="form-group row">
                <div class="col-sm-4">
                    @if (!empty($team->id))
                        <div class="col-sm-offset-3 col-sm-6">
                            <input type="hidden" name="id" id="id" value="{{ $team->id }}">
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
