<?php

class AdminsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('admins')->truncate();

		$admins = array(
			array(
				'admingroup_id'			=> 1,
				'name'		 			=> 'CREIDS Cpanel',
				'email'			 		=> 'admin@creids.net',
				'password'		 		=> Hash::make('creidsadmin'),
				'remember_token' 		=> '',
				'is_active'				=> true,
				'created_at'			=> date('Y-m-d H:i:s', time()),
				'updated_at'			=> date('Y-m-d H:i:s', time()),
			),
		);

		// Uncomment the below to run the seeder
		DB::table('admins')->insert($admins);
	}

}
