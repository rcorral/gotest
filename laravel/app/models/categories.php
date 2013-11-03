<?php

class Categories extends Cartalyst\NestedSets\Nodes\EloquentNode {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'test_categories';

	protected $fillable = array(
		'name',
		'published',
	);

	/**
	 * The worker class which the model uses.
	 *
	 * @var string
	 */
	protected $worker = 'Cartalyst\NestedSets\Workers\IlluminateWorker';
}