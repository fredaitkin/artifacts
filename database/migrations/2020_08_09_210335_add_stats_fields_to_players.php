<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddStatsFieldsToPlayers extends Migration
{
    public function up()
    {
        Schema::table('players', function($table) {
            $table->string('position')->nullable();
            $table->decimal('average', 8, 3)->nullable();
            $table->integer('home_runs')->nullable();
            $table->integer('wins')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function($table) {
            $table->dropColumn('position');
            $table->dropColumn('average');
            $table->dropColumn('home_runs');
            $table->dropColumn('wins');
        });
    }
}
