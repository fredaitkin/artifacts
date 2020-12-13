<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMltToPlayers extends Migration
{
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->binary('minor_league_teams')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('minor_league_teams');
        });
    }
}
