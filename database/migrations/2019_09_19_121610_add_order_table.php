<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('orders', function (Blueprint $t) {
			 $t->unsignedInteger('user_id')->default(0);
             $t->string('order_type', 20)->default('TICKET');
			 $t->decimal('cart_amount', 13, 2)->default(0);
			 /*
			 $t->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			 */
        });
		
		 Schema::table('events', function (Blueprint $t) {
			$t->decimal('cart_sales_volume', 13, 2)->default(0);
			 $t->decimal('cart_organiser_fees_volume', 13, 2)->default(0);
        });
		
		Schema::table('affiliates', function (Blueprint $t) {
			$t->decimal('cart_sales_volume', 13, 2)->default(0);
			$t->integer('cart_items_sold')->default(0);
        });
		
		 Schema::table('competitions', function (Blueprint $t) {
			$t->decimal('sales_volume', 13, 2)->default(0);
			 $t->decimal('organiser_fees_volume', 13, 2)->default(0);
        });
    }
	
	

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
           
		   $table->dropColumn('user_id');
			$table->dropColumn('order_type');
			$table->dropColumn('cart_amount');
			
        });
		 Schema::table('events', function (Blueprint $table) {
			 $table->dropColumn('cart_sales_volume');
			 $table->dropColumn('cart_organiser_fees_volume');

        });
		
		 Schema::table('affiliates', function (Blueprint $table) {
			$table->dropColumn('cart_sales_volume');
			$table->dropColumn('cart_items_sold');
        });
		
		Schema::table('competitions', function (Blueprint $table) {
			 $table->dropColumn('sales_volume');
			 $table->dropColumn('organiser_fees_volume');

        });
    }
}
