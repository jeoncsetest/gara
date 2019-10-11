<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservedCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserved_competitions', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('competition_id');
            $table->integer('event_id');
			$table->integer('user_id');
            $table->integer('quantity_reserved');
            $table->datetime('expires');
            $table->string('session_id', 45);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserved_competitions');
    }
}
