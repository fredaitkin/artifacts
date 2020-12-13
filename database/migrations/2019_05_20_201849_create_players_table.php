<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('team');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('draft_year')->nullable();
            $table->string('draft_round')->nullable();
            $table->integer('draft_position')->nullable();
            $table->timestamps();       
        });
    }

    public function down()
    {
        Schema::dropIfExists('players');
    }
}
