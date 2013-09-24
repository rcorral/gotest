<?php

use Illuminate\Database\Migrations\Migration;

class Mod20130920TestSessions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_sessions', function($table)
		{
			$table->timestamps();
			$table->softDeletes();
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
			$table->dropColumn('created_at');
			$table->dropColumn('updated_at');
			$table->dropColumn('deleted_at');
		});
	}

}