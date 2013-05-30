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

		DB::statement("INSERT INTO `{$prefix}test_question_types` (`id`, `type`, `title`, `html`)
			VALUES
				(1,'mcsa','Multiple choice single answer','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"radio\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),
				(2,'mcma','Multiple choice multiple answer','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<label for=\"min-answers-QID\">Minimum Answers: </label>\r\n	<input type=\"text\" name=\"questions[QID][min_answers]\" value=\"\" id=\"min-answers-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"checkbox\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),
				(3,'fitb','Fill in the blank','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"radio\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),
				(4,'fitbma','Fill in the blank multiple answer','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<label for=\"min-answers-QID\">Minimum Answers: </label>\r\n	<input type=\"text\" name=\"questions[QID][min_answers]\" value=\"\" id=\"min-answers-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"checkbox\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),
				(5,'essay','Essay','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<div clas=\"clr\"></div>\r\n</div>');"
		);
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
