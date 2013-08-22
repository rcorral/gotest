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

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = array('id');

	public static function load_populate( $id, $key = null, $questions = true )
	{
		$instance = parent::load_populate( $id, $key );

		if ( $questions )
			$instance->load_questions();

		return $instance;
	}

	/**
	 * Load questions for tests
	 */
	protected function load_questions()
	{
		if ( !$this->id )
		{
			$this->questions = array();
			return;
		}

		$this->questions = DB::table('test_questions')
			->select('test_questions.*')
			->addSelect('test_question_types.type AS tqt_type')
			->join('test_question_types', 'test_question_types.id', '=', 'test_questions.question_type', 'left')
			->where('test_questions.test_id', (int) $this->id)
			->orderBy('test_questions.order')
			->get()
			;

		foreach ( $this->questions as &$question )
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

	/**
	 * Cehck
	 */
	function check()
	{
		// Check for valid title
		if ( trim($this->title ) == '')
			throw new Exception(JText::_('COM_TESTS_WARNING_PROVIDE_VALID_NAME'));

		if ( empty($this->alias) )
			$this->alias = $this->title . date('Y-m-d H:i:s');

		$this->alias = Helper::string_url_safe($this->alias);

		if ( !$this->exists )
			$this->created_by = Helper::get_current_user()->id;

		// Check for valid category
		if ( !trim($this->catid) )
			throw new Exception(JText::_('COM_TESTS_WARNING_CATEGORY'));

		$test = Test::where('alias', $this->alias)->where('catid', $this->catid)->first();
		if ( $test && ($test->id != $this->id || !$this->id) )
			throw new Exception(JText::_('COM_TESTS_ERROR_UNIQUE_ALIAS'));

		return true;
	}

}