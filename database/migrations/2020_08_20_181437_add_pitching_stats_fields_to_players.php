<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddPitchingStatsFieldsToPlayers extends Migration
{
    public function up()
    {
        Schema::table('players', function($table) {
            $table->integer('losses')->nullable();
            $table->integer('saves')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function($table) {
            $table->dropColumn('losses');
            $table->dropColumn('saves');
        });
    }
}
