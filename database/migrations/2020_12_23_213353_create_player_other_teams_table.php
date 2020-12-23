<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerOtherTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('player_other_teams', function (Blueprint $table) {
            $table->integer('player_id')->unsigned()->index();
            $table->foreign('player_id')->references('id')->on('players');
            $table->integer('other_teams_id')->unsigned()->index();
            $table->foreign('other_teams_id')->references('id')->on('other_teams');
        });
    }

    public function down()
    {
        Schema::dropIfExists('player_other_teams');
    }
}
