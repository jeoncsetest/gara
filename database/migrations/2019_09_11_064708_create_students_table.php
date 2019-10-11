<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $t) {
			$t->increments('id');
			$t->string('school_eps')->index();
			$t->unsignedInteger('school_id')->nullable();
			$t->unsignedInteger('user_id')->nullable();
            $t->nullableTimestamps();
			$t->softDeletes();

            $t->string('name');
			$t->string('surname');
			$t->dateTime('birth_date');
			$t->string('birth_place');
			$t->string('fiscal_code');
            $t->string('phone')->nullable();
            $t->string('email')->nullable();

            $t->foreign('school_eps')->references('eps')->on('schools')->onDelete('cascade');
			/*$t->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
