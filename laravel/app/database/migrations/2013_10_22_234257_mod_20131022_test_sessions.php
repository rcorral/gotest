<?php

use Illuminate\Database\Migrations\Migration;

class Mod20131022TestSessions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_sessions', function($table)
		{
			$table->integer('last_question')->unsigned()->after('is_active');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('test_sessions', function($table)
		{
			$table->dropColumn('last_question');
		});
	}

}