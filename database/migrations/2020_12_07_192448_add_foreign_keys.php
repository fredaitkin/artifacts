<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('relocated_to')->references('team')->on('teams');
            $table->foreign('relocated_from')->references('team')->on('teams');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
        });
    }
}
