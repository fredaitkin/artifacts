<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerMinorLeagueTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('player_minor_league_teams', function (Blueprint $table) {
            $table->integer('player_id')->unsigned()->index();
            $table->foreign('player_id')->references('id')->on('players');
            $table->integer('mlt_id')->unsigned()->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('player_minor_league_teams');
    }
}
