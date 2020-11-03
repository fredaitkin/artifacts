<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->string('class')->nullable(true)->change();
            $table->string('affiliate')->nullable(true)->change();
            $table->string('city')->nullable(true)->change();
            $table->string('state')->nullable(true)->change();
            $table->string('country')->nullable(true)->change();
            $table->string('league')->nullable(true)->change();
            $table->string('division')->nullable(true)->change();
        });
    }

}
