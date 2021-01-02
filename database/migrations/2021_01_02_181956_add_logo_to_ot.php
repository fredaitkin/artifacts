<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToOt extends Migration
{
    public function up()
    {
        Schema::table('other_teams', function (Blueprint $table) {
            $table->string('logo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('other_teams', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
}
