<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->string('team', 3)->primary();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('ground')->nullable();
            $table->integer('founded')->nullable();
            $table->integer('closed')->nullable();
            $table->binary('titles')->nullable();
            $table->string('logo')->nullable();
            $table->binary('other_names')->nullable();
            $table->string('relocated_to', 3)->nullable();
            $table->string('relocated_from', 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
