<?php

use Illuminate\Database\Migrations\Migration;

class Mod20131020TestSessions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_sessions', function($table)
		{
			$table->string('title', 255)->nullable()->after('user_id');
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
			$table->dropColumn('title');
		});
	}

}