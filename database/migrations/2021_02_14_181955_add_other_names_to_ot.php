<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherNamesToOt extends Migration
{
    public function up()
    {
        Schema::table('other_teams', function (Blueprint $table) {
            $table->string('other_names')->nullable();
        });
    }

    public function down()
    {
        Schema::table('other_teams', function (Blueprint $table) {
            $table->dropColumn('other_names');
        });
    }
}
