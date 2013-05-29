<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$prefix = Config::get('database.connections.mysql.prefix');
		DB::statement("CREATE TABLE `{$prefix}test_questions` (
			    `id` int(11) NOT NULL AUTO_INCREMENT,
			    `title` varchar(255) NOT NULL,
			    `test_id` int(11) NOT NULL,
			    `question_type` int(11) NOT NULL,
			    `seconds` int(11) NOT NULL,
			    `min_answers` tinyint(3) NOT NULL,
			    `media` varchar(255) NOT NULL,
			    `media_type` enum('','link','image','youtube') NOT NULL,
			    `order` int(11) NOT NULL,
			    PRIMARY KEY (`id`),
			    KEY `question_type` (`question_type`,`order`),
			    KEY `test_id` (`test_id`)
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
		Schema::drop('test_questions');
	}

}
