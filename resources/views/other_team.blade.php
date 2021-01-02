@extends('layouts.app')

@section('content')

    <div class="panel-body artifacts-submit-form-div">

        <h2 class="col-sm-4">@if (!empty($team->name)) {{ $team->name }} @else Add Team @endif</h2>

        @include('common.errors')

        <form action="/other-team" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if (empty($team->id))
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="name" class="control-label">Team</label>
                        <input type="text" name="name" class="form-control" value="@if (old('name')){{ old('team') }}@elseif (!empty($team->name)){{ $team->name }}@endif">
                    </div>
                </div>
            @else
                <input type="hidden" name="name" class="form-control" value="{{ $team->name }}">
            @endif

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="league" class="control-label">League</label>
                    <input type="text" name="league" class="form-control" value="@if (old('league')){{ old('league') }}@elseif (!empty($team->league)){{ $team->league }}@endif">
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
                    <label for="city" class="control-label">City</label>
                    <input type="text" name="city" class="form-control" value="@if (old('city')){{ old('city') }}@elseif (!empty($team->city)){{ $team->city }}@endif">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="country" class="control-label">Country</label>
                    <select class="form-control" name="country">
                        @foreach ($countries as $country)
                            <option value="{{ $country }}"  @if (old('country') && old('country') == $country) selected @elseif (!empty($team->country) && ($team->country == $country)) selected @endif>{{ $country }}</option>
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
