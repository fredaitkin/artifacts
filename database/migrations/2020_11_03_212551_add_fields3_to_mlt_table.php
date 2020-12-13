<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFields3ToMltTable extends Migration
{
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
