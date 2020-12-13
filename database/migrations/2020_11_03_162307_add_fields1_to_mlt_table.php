<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFields1ToMltTable extends Migration
{
    public function up()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->string('class');
            $table->string('affiliate');
        });
    }

    public function down()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->dropColumn('class');
            $table->dropColumn('affiliate');
        });
    }
}
