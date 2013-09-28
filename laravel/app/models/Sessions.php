<?php

class Sessions extends ModelBase
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_sessions';

	/**
	 * Primary key
	 */
	protected $primary_key = 'id';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('test_id', 'user_id', 'unique_id', 'is_active');

	/**
	 * Indicates if the model should soft delete.
	 *
	 * @var bool
	 */
	protected $softDelete = true;

	public static function get_sessions()
	{
		$user = Helper::get_current_user();

		$query = DB::table('test_sessions')
			->select('test_sessions.id', 'test_sessions.created_at',
				'test_sessions.is_active', 'test_sessions.unique_id',
				'test_tests.id AS test_id', 'test_tests.title', 'test_tests.sub_title', 'test_tests.anon',
				'test_answers.user_id', 'test_answers.anon_user_id' )
			->join('test_tests', 'test_tests.id', '=', 'test_sessions.test_id')
			->join('test_answers', 'test_answers.session_id', '=', 'test_sessions.id')
			->where('test_sessions.user_id', $user->id)
			->whereNull('test_sessions.deleted_at')
			->groupBy(DB::raw('test_sessions.`id`, IF( test_tests.`anon` = 1, test_answers.`anon_user_id`, test_answers.`user_id` )'))
			;

		// Filter by active
		// $active = $this->getState( 'filter.active' );
		if ( false && null != $active ) {
			$query->where( 'test_sessions.active` = ' . (int) $active );
		}

		// This is ugly and there is probably a better way
		$a = $query->getBindings();
		$_query = DB::table(DB::raw('(' . str_replace('?', array_shift($a), $query->toSql()). ') AS q'));

		$_query->select('id', 'created_at', 'is_active', 'unique_id', 'test_id', 'title', 'sub_title',
				DB::raw('IF(anon = 1, COUNT(anon_user_id), COUNT(user_id) ) AS `count`'))
			->orderBy('title', 'ASC')
			->groupBy('id')
			;

		return $_query->paginate(Helper::paginate_by());
	}

	public static function get_session( $id )
	{
		return Sessions::where('id', (int) $id)->first();
	}

	/**
	 * Gets all answers for test ordered by student
	 */
	public function get_student_answers( $test_anon = false )
	{
		$query = DB::table('test_sessions')
			->select('test_answers.question_id',
				DB::raw('IF(`test_answers`.`answer_id`, `test_question_options`.`title`, `test_answers`.`answer_text`) as `answer`'))
			->join('test_tests', 'test_tests.id', '=', 'test_sessions.test_id')
			->join('test_answers', 'test_answers.session_id', '=', 'test_sessions.id')
			->join('test_questions', 'test_questions.id', '=', 'test_answers.question_id')
			->join('test_question_options', 'test_question_options.id', '=', 'test_answers.answer_id', 'left')
			->where('test_sessions.id', $this->id)
			;

		if ( !$test_anon )
		{
			$query
				->addSelect('users.id AS user_id', DB::raw('CONCAT(`users`.`first_name`, \' \', `users`.`last_name`) As `name`'), 'users.email')
				->join('users', 'users.id', '=', 'test_answers.user_id')
				->orderBy('users.last_name', 'ASC')
				->orderBy('users.id', 'ASC')
				->orderBy('test_questions.order', 'ASC')
				;
		} else {
			$query
				->select('test_answers.anon_user_id')
				->orderBy('test_answers.anon_user_id', 'ASC')
				->orderBy('test_questions.order', 'ASC')
				;
		}

		return $query->get();
	}
}