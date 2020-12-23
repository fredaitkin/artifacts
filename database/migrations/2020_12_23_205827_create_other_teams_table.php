<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('other_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('league')->nullable();
            $table->integer('founded')->nullable();
            $table->integer('defunct')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('other_teams');
    }
}
