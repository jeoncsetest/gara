<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('account_id');
            $table->string('title')->default('');
            $table->string('type')->default('');
            $table->string('level')->default('');
            $table->string('category')->default('');
            $table->boolean('mp3_upload')->default(0);
            $table->decimal('price', 13, 2);
            $table->integer('max_competitors');
            $table->integer('total_subscription');

            $table->timestamps();
            $table->softDeletes();
			/*
			 'user_id'               => factory(App\Models\User::class)->create()->id,
        'edited_by_user_id'     => factory(App\Models\User::class)->create()->id,
        'account_id'            => factory(App\Models\Account::class)->create()->id,
        'order_id'              => factory(App\Models\Order::class)->create()->id,
        'event_id'              => factory(App\Models\Event::class)->create()->id,
        'title'                 => $faker->name,
        'description'           => $faker->text,
        'price'                 => 50.00,
        'max_per_person'        => 4,
        'min_per_person'        => 1,
        'quantity_available'    => 50,
        'quantity_sold'         => 0,
        'start_sale_date'       => Carbon::now(),
        'end_sale_date'         => Carbon::now()->addDays(20),
        'sales_volume'          => 0,
        'organiser_fees_volume' => 0,
        'is_paused'             => 0,
        'public_id'             => null,
        'sort_order'            => 0,
        'is_hidden'             => false
			*/

            // Add events table foreign key
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
        Schema::dropIfExists('competitions');
    }
}
