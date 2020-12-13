<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddDebutYearToPlayers extends Migration
{

    public function up()
    {
        Schema::table('players', function($table) {
            $table->integer('debut_year')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function($table) {
            $table->dropColumn('debut_year');
        });
    }
}
