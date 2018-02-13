<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryrepairsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventoryrepairs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->integer('branch_id')->unsigned();
			$table->integer('trans_id')->unsigned()->nullable();
			$table->date('date');
			$table->integer('amount');
			$table->integer('last_stock');
			$table->integer('final_stock');
			$table->string('status');
			$table->string('note');
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
		Schema::drop('inventoryrepairs');
	}

}
