<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$prefix = Config::get('database.connections.mysql.prefix');
		DB::statement("CREATE TABLE `{$prefix}test_sessions` (
			   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			   `test_id` int(11) unsigned NOT NULL,
			   `user_id` int(11) unsigned NOT NULL,
			   `unique_id` varchar(32) NOT NULL,
			   `is_active` tinyint(3) NOT NULL,
			   `date` datetime NOT NULL,
			   PRIMARY KEY (`id`),
			   KEY `user_id` (`user_id`),
			   KEY `test_id` (`test_id`),
			   KEY `unique_id` (`unique_id`),
			   KEY `is_active` (`is_active`)
			 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('test_sessions');
	}

}
