@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Demographics</h2>

        <div class="row">
            <div id="pop1-div" class="col"></div>
                <?= $lava->render('PieChart', 'Popularity', 'pop1-div') ?>

            <div id="pop2-div" class="col"></div>
                <?= $lava->render('BarChart', 'ComparativePopularity', 'pop2-div') ?>

            <div id="pop3-div" class="col"></div>
                <?= $lava->render('PieChart', 'Population', 'pop3-div') ?>
        </div>

      @include('common.errors')

    </div>
@endsection
