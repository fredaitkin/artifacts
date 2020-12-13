<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFoundedFieldToMltTable extends Migration
{
    public function up()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->integer('founded')->nullable();
        });
    }

    public function down()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
              $table->dropColumn('founded');
        });
    }
}
