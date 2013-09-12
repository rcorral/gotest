<?php

define('URL_FORMAT', 
	'/^(https?):\/\/'.                                         // protocol
	'(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+'.         // username
	'(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?'.      // password
	'@)?(?#'.                                                  // auth requires @
	')((([a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)*'.           // domain segments AND
	'[a-z][a-z0-9-]*[a-z0-9]'.                                 // top level domain  OR
	'|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}'.
	'(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])'.                 // IP address
	')(:\d+)?'.                                                // port
	')(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*'. // path
	'(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)'.      // query string
	'?)?)?'.                                                   // path and query string optional
	'(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?'.      // fragment
	'$/i');

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
	protected $guarded = array('id', 'created_at', 'created_by', 'updated_at');

	/**
	 * Indicates if the model should soft delete.
	 *
	 * @var bool
	 */
	protected $softDelete = true;

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

		$questions = DB::table('test_questions')
			->select('test_questions.*')
			->addSelect('test_question_types.type AS tqt_type')
			->join('test_question_types', 'test_question_types.id', '=', 'test_questions.question_type', 'left')
			->where('test_questions.test_id', (int) $this->id)
			->orderBy('test_questions.order')
			->get()
			;

		foreach ( $questions as &$question )
		{
			$question->options = DB::table('test_question_options')
				->where('question_id', $question->id)
				->get()
				;
		}
		$this->questions = $questions;
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
			throw new Exception(Lang::get('tests.warning_provide_valid_name'));

		// if ( empty($this->alias) )
			$this->alias = $this->title;

		$this->alias = Helper::string_url_safe($this->alias);

		if ( !$this->exists )
			$this->created_by = Helper::get_current_user()->id;
		elseif ( $this->created_by != Helper::get_current_user()->id )
			throw new Exception(Lang::get('all.invalid_request'));

		// Check for valid category
		if ( !trim($this->catid) )
			throw new Exception(Lang::get('tests.warning_category'));

		$test = Test::where('alias', $this->alias)->where('catid', $this->catid)->first();
		if ( $test && ($test->id != $this->id || !$this->id) )
			throw new Exception(Lang::get('tests.error_unique_alias'));

		return true;
	}

	/**
	 * Saves questions that are submitted when editing a test
	 * It saves questions in order and updates or adds questions if new
	 */
	public function add_test_questions( $data, $test_id )
	{
		if ( empty( $data ) )
			return true;

		$question_ids = array();
		foreach ( $data as $question_id => $question )
		{
			$question['question'] = trim( $question['question'] );
			if ( empty( $question['question'] ) || !$test_id || !$question['type_id'] )
				continue;

			$table = DB::table('test_questions');
			$_data = array(
				'title' => $question['question'],
				'test_id' => $test_id,
				'question_type' => $question['type_id'],
				'seconds' => $question['seconds'],
				'min_answers' => @$question['min_answers'] ? $question['min_answers'] : 0,
				'media' => $this->clean_media_url( $question['media'], @$question['media_type'] ),
				'media_type' => @$question['media_type'] ? $question['media_type'] : ''
				);

			// This means that this question already exists so lets add the id to the array
			try
			{
				// Update
				if ( substr( $question_id, 0, 1 ) != 'n' )
				{
					$table->where('id', (int) $question_id)
						->update($_data)
						;
					$qid = (int) $question_id;
				}
				// Insert
				else
				{
					$_data['order'] = (int) DB::table('test_questions')
						->where( 'test_id', (int) $test_id )
						->max('order') + 1
						;
					$qid = (int) $table->insertGetId($_data);
				}
			}
			catch (Exception $e)
			{
				// Should we abort completely? or just continue?
				$errors[] = "Question there was an error with question: {$question['question']}";
				continue;
			}

			$question_ids[] = $qid;

			// Lets add all the options
			$tuples = array();

			if ( isset( $question['options'] ) && !empty( $question['options'] ) )
			{
				// Delete all previous question options
					DB::table('test_question_options')
					->where('question_id', $qid)
					->delete()
					;

				foreach ( $question['options'] as $option_id => $option )
				{
					$option = trim( $option );
					if ( empty( $option ) )
						continue;

					try
					{
						DB::table('test_question_options')->insert(
							array(
								'question_id' => $qid,
								'title' => $option,
								'valid' => @in_array( $option_id, @$question['answers'] )
							))
							;
					}
					catch (Exception $e)
					{
						$errors[] = "Some answers weren't saved on question: {$question['question']}";
						continue;
					}
				}
			}
		}

		// Delete all questions that are not on request
		DB::table('test_questions')
			->where('test_id', (int) $test_id )
			->whereNotIn('id', $question_ids)
			->delete()
			;

		if ( !empty( $errors ) )
			throw new Exception(implode("\n", $errors));

		return true;
	}

	public function clean_media_url( $url, $type )
	{
		if ( !$url ) return '';

		return preg_match(URL_FORMAT, $url) ? $url : '';
	}

	/**
	 * Hooks
	 */
	public static function boot()
	{
		parent::boot();

		Test::saved(function($test)
		{
			$test->add_test_questions(Input::get('questions'), $test->id);
		});

		Test::deleted(function($test)
		{
		});
	}
}