<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentdetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paymentdetails', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('payment_id')->unsigned();
			$table->integer('sale_id')->unsigned();
			$table->double('price_payment', 15, 2);
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
		Schema::drop('paymentdetails');
	}

}
