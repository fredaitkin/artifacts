@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body artifacts-submit-form-div">

        <h2 class="col-sm-3">Demographics</h2>
    
      <div id="pop1-div" style="width:400px;border:1px solid black;"></div>
        <?= $lava->render('PieChart', 'Popularity', 'pop1-div') ?>

      <div id="pop2-div" style="width:400px;border:1px solid black;"></div>
        <?= $lava->render('PieChart', 'Population', 'pop2-div') ?>

        @include('common.errors')



    </div>
@endsection
