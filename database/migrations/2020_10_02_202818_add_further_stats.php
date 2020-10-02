<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFurtherStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('games_started')->nullable();
            $table->decimal('innings_pitched', 8, 1)->nullable();       
            $table->integer('strike_outs')->nullable();
            $table->decimal('whip', 5, 2)->nullable(); 
            $table->integer('hits')->nullable();
            $table->integer('runs')->nullable();
            $table->integer('stolen_bases')->nullable();
            $table->decimal('obp', 4, 3)->nullable(); 
            $table->decimal('ops', 4, 3)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('games_started');
            $table->dropColumn('innings_pitched');
            $table->dropColumn('strike_outs');
            $table->dropColumn('whip');
            $table->dropColumn('hits');
            $table->dropColumn('runs');
            $table->dropColumn('stolen_bases');
            $table->dropColumn('obp');
            $table->dropColumn('ops');
        });
    }
}