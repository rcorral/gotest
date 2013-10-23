<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$prefix = Config::get('database.connections.mysql.prefix');
		DB::statement("CREATE TABLE `{$prefix}test_tests` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) NOT NULL,
			  `alias` varchar(255) NOT NULL,
			  `sub_title` varchar(255) NOT NULL,
			  `catid` int(10) unsigned NOT NULL,
			  `anon` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Tells if test can be submitted anonymously',
			  `published` tinyint(1) NOT NULL,
			  `created` datetime NOT NULL,
			  `created_by` int(10) unsigned NOT NULL,
			  `modified` datetime NOT NULL,
			  `modified_by` int(11) NOT NULL,
			  `checked_out` int(11) NOT NULL,
			  `checked_out_time` datetime NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `alias` (`alias`),
			  KEY `created_by` (`created_by`),
			  KEY `catid` (`catid`),
			  KEY `anon` (`anon`)
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
		Schema::drop('test_tests');
	}

}
