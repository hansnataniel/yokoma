<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockgooddetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stockgooddetails', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('stockgood_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->integer('amount');
			$table->boolean('type'); // 0 = out or 1 = in
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
		Schema::drop('stockgooddetails');
	}

}
