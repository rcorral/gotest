<?php

use Illuminate\Database\Migrations\Migration;

class Mod20131101TestCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$default_subjects = array(
			'Language Arts',
			'Reading',
			'Handwriting',
			'History',
			'Health, Safety & Physical Education',
			'English Grammar & Composition',
			'Spelling',
			'Literature',
			'Math',
			'Science' => array(
				'Life',
				'Earth-Space',
				),
			'Social Studies' => array(
				'World Studies',
				'American & North Carolina',
				)
			);

		$parse = function( $arr, $parent_id ) use ( &$parse )
		{
			foreach ( $arr as $key => $subject )
			{
				if ( is_array($subject) )
				{
					$parent = SubjectsController::add($key, $parent_id);
					$parse($subject, $parent->id);
					continue;
				}

				SubjectsController::add($subject, $parent_id);
			}
		};

		$parse($default_subjects, 1);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('TRUNCATE `test_categories`;');

		// Make a new root node
		$root = new Categories(array('name' => 'root', 'published' => 1));
		$root->makeRoot();
	}

}