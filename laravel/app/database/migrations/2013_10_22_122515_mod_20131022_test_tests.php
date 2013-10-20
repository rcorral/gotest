<?php

use Illuminate\Database\Migrations\Migration;

class Mod20131022TestTests extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_tests', function($table)
		{
			$table->integer('seconds')->unsigned()->after('published');
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
			$table->dropColumn('seconds');
		});
	}

}