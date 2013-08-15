<?php

use Illuminate\Database\Migrations\Migration;

class CreateTestCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('test_categories', function(Illuminate\Database\Schema\Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name', 255);
			$table->integer('lft')->unsigned()->index();
			$table->integer('rgt')->unsigned()->index();
			$table->integer('tree')->unsigned()->index();
			$table->boolean('published')->index()->default(1);
			$table->integer('created_by')->unsigned()->default(0);
			$table->timestamps();
		});

		// Make a new root node
		$root = new Category(array('name' => 'root', 'published' => 1));
		$root->makeRoot();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('test_categories');
	}

}