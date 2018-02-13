<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('branch_id')->unsigned();
			$table->integer('salesman_id1')->unsigned();
			$table->string('commission1');
			$table->integer('salesman_id2')->unsigned();
			$table->string('commission2');
			$table->boolean('from_net');
			$table->string('name');
			$table->string('address');
			$table->string('no_telp');
			$table->string('cp_name');
			$table->string('cp_no_hp');
			$table->integer('due_date');
			$table->boolean('is_active');
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
		Schema::drop('customers');
	}

}
