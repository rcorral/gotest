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
	 * Get's the session object from the database from the short unique id that was passed
	 *
	 * @param  test_id int Test id
	 * @param  unique_short string A portion of the uniqueue id for the session
	 * @return object The test session object
	 */
	public function get_session_from_short_id( $test_id, $unique_short )
	{
		static $return;

		if ( $return ) return $return;

		$return = (object) array(
			'test_id' => $test_id,
			'unique_id' => '',
			'is_active' => 0
			);

		if ( $return->test_id && $unique_short )
		{
			$result = DB::table('test_sessions')
				->select('unique_id', 'is_active')
				->where('is_active', '1')
				->where('test_id', (int) $return->test_id )
				->where('unique_id', 'LIKE', $unique_short . '%' )
				->first()
				;
			$return->unique_id = @$result->unique_id;
			$return->is_active = @$result->is_active;
		}

		return $return;
	}
}