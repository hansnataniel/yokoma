<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('AdminsTableSeeder');
		$this->call('AdmingroupsTableSeeder');
		$this->call('SettingsTableSeeder');
	}

}
