<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatsFieldsToPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function($table) {
            $table->string('position')->nullable();
            $table->decimal('average', 8, 3)->nullable();
            $table->integer('home_runs')->nullable();
            $table->integer('wins')->nullable();
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
            $table->dropColumn('position');
            $table->dropColumn('average');
            $table->dropColumn('home_runs');
            $table->dropColumn('wins');
        });
    }
}
