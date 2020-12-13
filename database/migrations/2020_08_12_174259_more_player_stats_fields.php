<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class MorePlayerStatsFields extends Migration
{
    public function up()
    {
        Schema::table('players', function($table) {
            $table->integer('games')->nullable();
            $table->integer('at_bats')->nullable();
            $table->integer('rbis')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function($table) {
            $table->dropColumn('games');
            $table->dropColumn('at_bats');
            $table->dropColumn('rbis');
        });
    }
}
