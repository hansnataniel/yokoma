<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales', function(Blueprint $table) {
			$table->increments('id');
			$table->string('no_invoice')->nullable();
			$table->integer('branch_id')->unsigned();
			$table->integer('customer_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->date('date');
			$table->date('due_date');
			$table->double('price_total', 15, 2)->nullable();
			$table->double('recycle_total', 15, 2)->nullable();
			$table->double('owed', 15, 2)->nullable();
			$table->double('paid', 15, 2)->nullable();
			$table->integer('commission1')->nullable();
			$table->integer('commission2')->nullable();
			$table->boolean('from_net');
			$table->boolean('print');
			$table->string('status');
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
		Schema::drop('sales');
	}

}
