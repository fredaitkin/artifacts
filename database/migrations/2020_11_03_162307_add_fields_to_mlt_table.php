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
            $table->string('class');
            $table->string('affiliate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('minor_league_teams', function (Blueprint $table) {
            $table->dropColumn('class');
            $table->dropColumn('affiliate');
        });
    }
}
