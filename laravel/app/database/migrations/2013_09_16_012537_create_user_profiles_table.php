<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_profiles', function($table)
		{
			$table->integer('user_id')->unsigned();
			$table->string('profile_key', 100);
			$table->string('profile_value', 255);
			$table->integer('ordering')->default(0)->unsigned();
			$table->unique(array('user_id', 'profile_key'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_profiles');
	}

}