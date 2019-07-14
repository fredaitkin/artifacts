@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body artifacts-submit-form-div">

        <h2 class="col-sm-3">Demographics</h2>
      
      <div id="pop-div" style="width:800px;border:1px solid black"></div>
        <?= $lava->render('PieChart', 'Popularity', 'pop-div') ?>

        @include('common.errors')



    </div>
@endsection
