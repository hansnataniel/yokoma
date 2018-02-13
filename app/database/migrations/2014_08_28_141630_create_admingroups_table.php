<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdmingroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admingroups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');

			$table->boolean('admingroup_c');
			$table->boolean('admingroup_r');
			$table->boolean('admingroup_u');
			$table->boolean('admingroup_d');

			$table->boolean('admin_c');
			$table->boolean('admin_r');
			$table->boolean('admin_u');
			$table->boolean('admin_d');

			$table->boolean('setting_u');

			$table->boolean('user_c');
			$table->boolean('user_r');
			$table->boolean('user_u');
			$table->boolean('user_d');

			$table->boolean('customer_c');
			$table->boolean('customer_r');
			$table->boolean('customer_u');
			$table->boolean('customer_d');

			$table->boolean('branch_c');
			$table->boolean('branch_r');
			$table->boolean('branch_u');
			$table->boolean('branch_d');

			$table->boolean('salesman_c');
			$table->boolean('salesman_r');
			$table->boolean('salesman_u');
			$table->boolean('salesman_d');

			$table->boolean('product_c');
			$table->boolean('product_r');
			$table->boolean('product_u');
			$table->boolean('product_d');

			$table->boolean('stockgoods_c');
			$table->boolean('stockgoods_r');
			$table->boolean('stockgoods_u');
			$table->boolean('stockgoods_d');

			$table->boolean('stocksecond_c');
			$table->boolean('stocksecond_r');
			$table->boolean('stocksecond_u');
			$table->boolean('stocksecond_d');

			$table->boolean('stockrepair_c');
			$table->boolean('stockrepair_r');
			$table->boolean('stockrepair_u');
			$table->boolean('stockrepair_d');

			$table->boolean('sales_c');
			$table->boolean('sales_r');
			$table->boolean('sales_u');
			$table->boolean('sales_d');

			$table->boolean('salesreturn_c');
			$table->boolean('salesreturn_r');
			$table->boolean('salesreturn_u');
			$table->boolean('salesreturn_d');

			$table->boolean('payment_c');
			$table->boolean('payment_r');
			$table->boolean('payment_u');
			$table->boolean('payment_d');

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
		Schema::drop('admingroups');
	}

}
