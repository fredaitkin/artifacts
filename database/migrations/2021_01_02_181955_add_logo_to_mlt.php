<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToMlt extends Migration
{
    public function up()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->string('logo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
}
