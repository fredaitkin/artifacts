<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToPlayers extends Migration
{
    public function up()
    {
        Schema::table('players', function($table) {
            $table->string('photo')->nullable();
            $table->binary('previous_teams')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function($table) {
            $table->dropColumn('photo');
            $table->dropColumn('previous_teams');
        });
    }
}
