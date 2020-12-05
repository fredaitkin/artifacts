<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerPreviousTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_previous_teams', function (Blueprint $table) {
            $table->integer('player_id')->unsigned()->index();
            $table->foreign('player_id')->references('id')->on('players');
            $table->string('team', 3)->index();
            $table->foreign('team')->references('team')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_previous_teams');
    }
}
