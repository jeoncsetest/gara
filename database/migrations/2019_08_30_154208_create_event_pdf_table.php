<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventPdfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_pdfs', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('event_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('account_id');
			$table->string('pdf_path')->default('');
            $table->timestamps();
			$table->softDeletes();
			
			// Add event's pdf table foreign key
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_pdfs');
    }
}
