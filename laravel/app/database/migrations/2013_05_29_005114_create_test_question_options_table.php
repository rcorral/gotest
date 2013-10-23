<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestQuestionOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$prefix = Config::get('database.connections.mysql.prefix');
		DB::statement("CREATE TABLE `{$prefix}test_question_options` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `question_id` int(10) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `valid` tinyint(3) NOT NULL COMMENT 'If 1 then it means the answer is a correct one.',
			  PRIMARY KEY (`id`),
			  KEY `question_id` (`question_id`),
			  KEY `valid` (`valid`)
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
		Schema::drop('test_question_options');
	}

}
