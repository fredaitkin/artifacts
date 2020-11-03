<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreviousTeamsFieldToMltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->binary('previous_teams')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->dropColumn('previous_teams');
        });
    }
}
