<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $t) {
			$t->increments('id');
			$t->unsignedInteger('user_id')->unique();
            $t->nullableTimestamps();
			$t->softDeletes();
			$t->string('eps')->unique();
            $t->string('name')->unique();
            $t->string('phone')->unique();;
            $t->string('email')->unique();
            $t->string('password');
			$t->string('city');
			$t->string('place');
			$t->string('address');
			
            $t->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
