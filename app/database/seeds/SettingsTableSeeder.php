<?php

class SettingsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('settings')->truncate();

		$settings = array(
			array(
				'session_lifetime' 			=> '60',
				'admin_url'					=> Crypt::encrypt('manage'),
				'name'						=> '',
				'maintenance'				=> false,
				'created_at'				=> date('Y-m-d H:i:s', time()),
				'updated_at'				=> date('Y-m-d H:i:s', time()),
			),
		);

		// Uncomment the below to run the seeder
		DB::table('settings')->insert($settings);
	}

}
