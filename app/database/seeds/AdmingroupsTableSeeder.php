<?php

class AdmingroupsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('admingroups')->truncate();

		$admingroups = array(
			array(
				'name'				=> 'Admin',

				'admingroup_c'		=> true,
				'admingroup_r'		=> true,
				'admingroup_u'		=> true,
				'admingroup_d'		=> true,

				'admin_c'			=> true,
				'admin_r'			=> true,
				'admin_u'			=> true,
				'admin_d'			=> true,

				'setting_u'			=> true,

				'user_c'			=> true,
				'user_r'			=> true,
				'user_u'			=> true,
				'user_d'			=> true,

				'customer_c'		=> true,
				'customer_r'		=> true,
				'customer_u'		=> true,
				'customer_d'		=> true,

				'branch_c'			=> true,
				'branch_r'			=> true,
				'branch_u'			=> true,
				'branch_d'			=> true,

				'salesman_c'		=> true,
				'salesman_r'		=> true,
				'salesman_u'		=> true,
				'salesman_d'		=> true,

				'product_c'			=> true,
				'product_r'			=> true,
				'product_u'			=> true,
				'product_d'			=> true,

				'stockgoods_c'		=> true,
				'stockgoods_r'		=> true,
				'stockgoods_u'		=> true,
				'stockgoods_d'		=> true,

				'stocksecond_c'		=> true,
				'stocksecond_r'		=> true,
				'stocksecond_u'		=> true,
				'stocksecond_d'		=> true,

				'stockrepair_c'		=> true,
				'stockrepair_r'		=> true,
				'stockrepair_u'		=> true,
				'stockrepair_d'		=> true,

				'sales_c'			=> true,
				'sales_r'			=> true,
				'sales_u'			=> true,
				'sales_d'			=> true,

				'salesreturn_c'		=> true,
				'salesreturn_r'		=> true,
				'salesreturn_u'		=> true,
				'salesreturn_d'		=> true,

				'payment_c'			=> true,
				'payment_r'			=> true,
				'payment_u'			=> true,
				'payment_d'			=> true,

				'is_active'			=> true,
				'created_at'		=> date('Y-m-d H:i:s', time()),
				'updated_at'		=> date('Y-m-d H:i:s', time()),
			),
		);

		// Uncomment the below to run the seeder
		DB::table('admingroups')->insert($admingroups);
	}

}
