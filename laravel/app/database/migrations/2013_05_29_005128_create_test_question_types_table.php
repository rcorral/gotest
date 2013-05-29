<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestQuestionTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		$prefix = Config::get('database.connections.mysql.prefix');
		DB::statement("CREATE TABLE `{$prefix}test_question_types` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `type` varchar(16) NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `html` text NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `type` (`type`)
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
		Schema::drop('test_question_types');
	}

}
