<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddEraToPlayers extends Migration
{
    public function up()
    {
        Schema::table('players', function($table) {
            $table->decimal('era', 8, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function($table) {
            $table->dropColumn('era');
        });
    }
}
