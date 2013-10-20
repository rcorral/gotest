<?php

use Illuminate\Database\Migrations\Migration;

class Mod20131021TestAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_answers', function($table)
		{
			$table->dateTime('created_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('test_answers', function($table)
		{
			$table->dropColumn('created_at');
		});
	}

}