<?php

class Test extends ModelBase
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_tests';

	/**
	 * Primary key
	 */
	protected $primary_key = 'id';

	public function load( $id, $key = null )
	{
		$object = parent::load( $id, $key );

		$this->load_questions( $object );

		return $object;
	}

	/**
	 * Load questions for tests
	 */
	protected function load_questions( &$object )
	{
		if ( !$object->id )
		{
			$object->questions = array();
			return $object;
		}

		$object->questions = DB::table('test_questions')
			->select('test_questions.*')
			->addSelect('test_question_types.type AS tqt_type')
			->join('test_question_types', 'test_question_types.id', '=', 'test_questions.question_type', 'left')
			->where('test_questions.test_id', (int) $object->id)
			->orderBy('test_questions.order')
			->get()
			;

		foreach ( $object->questions as &$question )
		{
			$question->options = DB::table('test_question_options')
				->where('question_id', $question->id)
				->get()
				;
		}
	}

	/**
	 * Get's question templates
	 *
	 * @return string
	 */
	public function get_templates()
	{
		return (object) Helper::get_question_templates();
	}
}