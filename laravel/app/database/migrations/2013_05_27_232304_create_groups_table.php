<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$prefix = Config::get('database.connections.mysql.prefix');
		DB::statement("CREATE TABLE `{$prefix}groups` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `permissions` text COLLATE utf8_unicode_ci,
		  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `groups_name_unique` (`name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		DB::statement("INSERT INTO `{$prefix}groups` (`id`, `name`, `permissions`, `created_at`, `updated_at`)
		VALUES
			(1,'Admin','{\"superuser\":1,\"teacher\":1,\"student\":1}','2013-05-27 03:02:51','2013-05-27 03:02:51'),
			(2,'Teacher','{\"teacher\":1,\"student\":1}','2013-05-27 03:00:20','2013-05-27 03:00:20'),
			(3,'User','{\"student\":1}','2013-05-27 03:04:04','2013-05-27 03:04:04');
		");

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('groups');
	}

}
