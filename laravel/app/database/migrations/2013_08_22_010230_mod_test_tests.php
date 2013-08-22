<?php

use Illuminate\Database\Migrations\Migration;

class ModTestTests extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_tests', function($table)
		{
			$table->renameColumn('created', 'created_at');
			$table->renameColumn('modified', 'updated_at');
			$table->dropColumn('modified_by', 'checked_out', 'checked_out_time');
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
			$table->renameColumn('created_at', 'created');
			$table->renameColumn('updated_at', 'modified');
			$table->integer('modified_by')->after('modified');
			$table->integer('checked_out')->after('modified_by');
			$table->dateTime('checked_out_time')->after('checked_out');
		});
	}

}