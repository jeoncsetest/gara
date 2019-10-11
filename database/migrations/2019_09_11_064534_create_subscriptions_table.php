<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('competition_id')->index();
			$table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('event_id')->index();
            $table->string('mp3_path')->nullable();
            $table->string('group_name')->nullable();
            $table->string('level')->nullable();
            $table->string('category')->nullable();
            
			$table->integer('reference_index')->default(0);
			
            /*$table->integer('private_reference_number')->index();*/
			$table->string('private_reference_number', 15)->index();

            $table->nullableTimestamps();
            $table->softDeletes();

            $table->boolean('is_cancelled')->default(false);
            $table->boolean('has_arrived')->default(false);
            $table->dateTime('arrival_time')->nullable();

            $table->unsignedInteger('account_id')->index();
			
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
			$table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
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
        Schema::dropIfExists('subscriptions');
    }
}
