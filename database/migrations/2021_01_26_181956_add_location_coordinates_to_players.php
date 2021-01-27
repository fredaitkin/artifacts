<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationCoordinatesToPlayers extends Migration
{
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->binary('location_coordinates')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('location_coordinates');
        });
    }
}
