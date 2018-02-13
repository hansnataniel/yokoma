<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductrepairsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('productrepairs', function(Blueprint $table) {
			$table->increments('id');
			$table->string('no_invoice')->nullable();
			$table->integer('branch_id')->unsigned();
			$table->integer('customer_id')->unsigned();
			$table->integer('user_id')->unsigned()->nullable();
			$table->date('date');
			$table->double('price_total', 15, 2)->nullable();
			$table->string('status')->nullable();
			$table->string('keterangan')->nullable();
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
		Schema::drop('productrepairs');
	}

}
