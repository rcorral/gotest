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
			->select('test_sessions.id', 'test_sessions.date', 'test_sessions.is_active', 'test_sessions.unique_id',
				'test_tests.id AS test_id', 'test_tests.title', 'test_tests.sub_title', 'test_tests.anon',
				'test_answers.user_id', 'test_answers.anon_user_id' )
			->join('test_tests', 'test_tests.id', '=', 'test_sessions.test_id')
			->join('test_answers', 'test_answers.session_id', '=', 'test_sessions.id')
			->where('test_sessions.user_id', $user->id)
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

		$_query->select('id', 'date', 'is_active', 'unique_id', 'test_id', 'title', 'sub_title',
				DB::raw('IF(anon = 1, COUNT(anon_user_id), COUNT(user_id) ) AS `count`'))
			->orderBy('title', 'ASC')
			->groupBy('id')
			;

		return $_query->paginate(Helper::paginate_by());
	}
}