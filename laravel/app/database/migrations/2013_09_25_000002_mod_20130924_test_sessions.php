<?php

use Illuminate\Database\Migrations\Migration;

class Mod20130924TestSessions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_sessions', function($table)
		{
			$table->dropColumn('date');
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
			$table->dateTime('date');
		});
	}

}