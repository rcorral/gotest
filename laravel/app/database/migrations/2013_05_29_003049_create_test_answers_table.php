<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$prefix = Config::get('database.connections.mysql.prefix');
		DB::statement("CREATE TABLE `{$prefix}test_answers` (
			  `user_id` int(10) unsigned NOT NULL COMMENT 'Relation to _users',
			  `anon_user_id` varchar(8) NOT NULL COMMENT 'Unique ids for anonymous tests.',
			  `session_id` int(10) unsigned NOT NULL COMMENT 'Relation to _test_sessions',
			  `question_id` int(10) unsigned NOT NULL COMMENT 'Relation to _test_questions',
			  `answer_id` int(10) unsigned NOT NULL COMMENT 'Relation to _test_question_options',
			  `answer_text` mediumtext NOT NULL COMMENT 'For text type answers (essay)',
			  KEY `session_id` (`session_id`),
			  KEY `question_id` (`question_id`),
			  KEY `user_id` (`user_id`),
			  KEY `anon_user_id` (`anon_user_id`)
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
		Schema::drop('test_answers');
	}

}
