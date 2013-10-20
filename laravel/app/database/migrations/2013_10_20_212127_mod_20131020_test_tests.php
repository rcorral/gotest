<?php

use Illuminate\Database\Migrations\Migration;

class Mod20131020TestTests extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_tests', function($table)
		{
			$table->boolean('interactive')->after('catid');
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
			$table->dropColumn('interactive');
		});
	}

}