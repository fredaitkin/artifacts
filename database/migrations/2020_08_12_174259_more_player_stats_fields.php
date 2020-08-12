<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MorePlayerStatsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function($table) {
            $table->integer('games')->nullable();
            $table->integer('at_bats')->nullable();
            $table->integer('rbis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function($table) {
            $table->dropColumn('games');
            $table->dropColumn('at_bats');
            $table->dropColumn('rbis');
        });
    }
}
