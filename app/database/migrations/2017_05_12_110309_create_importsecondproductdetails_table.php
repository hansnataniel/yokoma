<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportsecondproductdetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('importsecondproductdetails', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('importsecondproduct_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->integer('qty');
			$table->double('price', 15, 2);
			$table->double('subtotal', 15, 2);
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
		Schema::drop('importsecondproductdetails');
	}

}

