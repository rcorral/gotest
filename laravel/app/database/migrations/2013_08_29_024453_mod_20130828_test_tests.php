<?php

use Illuminate\Database\Migrations\Migration;

class Mod20130828TestTests extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_tests', function($table)
		{
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
		Schema::table('test_tests', function($table)
		{
			$table->dropColumn('deleted_at');
		});
	}

}