<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToMlt extends Migration
{
    public function up()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->integer('defunct')->nullable();
            $table->string('other_names')->nullable();
        });
    }

    public function down()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->dropColumn('defunct');
            $table->dropColumn('other_names');
        });
    }
}
