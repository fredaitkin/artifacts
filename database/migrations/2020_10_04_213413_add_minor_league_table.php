<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinorLeagueTable extends Migration
{
    public function up()
    {
        Schema::create('minor_league_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('team');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('minor_league_teams');
    }
}
