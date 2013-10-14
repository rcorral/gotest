<?php
class Helper
{
	static function is_home()
	{
		static $home = null;

		if ( null !== $home ) {
			return $home;
		}

		$home = ('home' == Route::currentRouteName());

		return $home;
	}

	static function csrf_check()
	{
		if ( Session::token() != Input::get('_token') )
		{
			throw new Illuminate\Session\TokenMismatchException;
		}
	}

	static function prefix_table( $name )
	{
		return self::get_db_prefix() . $name;
	}

	static function get_db_prefix()
	{
		static $prefix;

		if ( !$prefix ) {
			$prefix = Config::get('database.connections.mysql.prefix');
		}

		return $prefix;
	}

	static function modal_html( $contents, $title = '', $footer = '' ) {}

	/**
	 * TEST METHODS
	 */

	static function get_question_types()
	{
		$types = array(
			'mcsa' => (object) array('id' => 1, 'type' => 'mcsa', 'title' => 'Multiple choice single answer'),
			'mcma' => (object) array('id' => 2, 'type' => 'mcma', 'title' => 'Multiple choice multiple answer'),
			'fitb' => (object) array('id' => 3, 'type' => 'fitb', 'title' => 'Fill in the blank'),
			'fitbma' => (object) array('id' => 4, 'type' => 'fitbma', 'title' => 'Fill in the blank multiple answer'),
			'essay' => (object) array('id' => 5, 'type' => 'essay', 'title' => 'Essay')
			);

		return $types;
	}

	static function get_question_templates()
	{
		$types = self::get_question_types();

		$templates = array();
		foreach ( $types as $type )
		{
			$templates[$type->type] = self::get_question_type($type->type);
		}

		return $templates;
	}

	/**
	 * Gets a question template
	 */
	static function get_question_type( $type )
	{
		if ( !$type ) return '';

		$questions = self::get_question_types();
		$options = array(
			'seconds' => true,
			'minimum_answers' => true,
			'media' => true,
			'answers' => true,
			'answer_type' => 'radio', // radio or checkbox
			);

		switch ( $type ) {
			case 'mcsa':
				$options['minimum_answers'] = false;
				break;

			case 'mcma':
				$options['answer_type'] = 'radio';
				break;

			case 'fitb':
				$options['minimum_answers'] = false;
				break;

			case 'fitbma':
				$options['answer_type'] = 'radio';
				break;

			case 'essay':
				$options['minimum_answers'] = false;
				$options['answers'] = false;
				break;

			default: return false;
		}

		$question = $questions[$type];
		$question->html = (string) View::make('tmpl.questions', $options);

		return $question;
	}

	static function paginate_by()
	{
		return 50;
	}

	/**
	 * Session methods
	 */

	/**
	 * Function will generate a new unique test session id for the given test_id and user_id
	 */
	static function generate_unique_test_id( $test_id, $user_id = null )
	{
		if ( !$user_id ) $user_id = static::get_current_user()->id;

		$test_id = (int) $test_id;
		$user_id = (int) $user_id;
		$date = date( 'Y-m-d H:i:s' );
		$unique_id = '';

		if ( !$test_id ) return false;

		$counter = 0;
		while ( !$unique_id )
		{
			if ( $counter ) $_unique = md5( $date . $test_id . $user_id . $counter );
			else $_unique = md5( $date . $test_id . $user_id );

			$query = DB::table('test_sessions')
				->select('id')
				->where('unique_id', $_unique)
				;
			if ( !$query->get() )
			{
				Sessions::create(array('test_id' => $test_id, 'user_id' => $user_id, 'unique_id' => $_unique,
					'is_active' => 1)
				);

				$unique_id = $_unique;
			}

			$counter++;
		}

		return $unique_id;
	}

	/**
	 * See if test is still active
	 */
	static function is_test_session_active( $test_id, $unique_id )
	{
		return DB::table('test_sessions')
			->select( 'is_active' )
			->where('test_id', (int) $test_id )
			->where('unique_id', $unique_id )
			->pluck('is_active')
			;
	}

	/**
	 * USER METHODS
	 */

	static function is_logged_in()
	{
		return Sentry::check();
	}

	static function get_current_user()
	{
		try
		{
			// Get the current active/logged in user
			return Sentry::getUser();
		}
		catch ( Cartalyst\Sentry\Users\UserNotFoundException $e )
		{
			// User wasn't found, should only happen if the user was deleted
			// when they were already logged in or had a "remember me" cookie set
			// and they were deleted.
			Helper::logout(false);
			App::abort(401, 'You are not authorized.');
		}
	}

	static public function get_user_by_id( $user_id )
	{
		try
		{
		    return Sentry::findUserById($user_id);
		}
		catch ( Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return false;
		}
	}

	static function get_group( $identifier )
	{
		if ( is_int( $identifier ) ) {
			try {
				return Sentry::getGroupProvider()->findById($identifier);
			} catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
				return false;
			}
		} else {
			try {
				return Sentry::getGroupProvider()->findByName($identifier);
			} catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
				return false;
			}
		}
	}

	static function get_api_user( $request_key = 'key' )
	{
		$model = Sentry::getUserProvider()->createModel();

		if ( !$user = $model->newQuery()->where('api_token', Input::get($request_key))->first())
			throw new UserNotFoundException("Invalid user.", 400);

		return $user;
	}

	/**
	 * @param  $credentials Array An array containing (email, password)
	 * @param  $remember Bool Remeber the user or not
	 */
	static function authenticate( $credentials, $remember = false )
	{
		try {
			// Try to authenticate the user
			return Sentry::authenticate($credentials, (bool) $remember);
		} catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
			$error = 'Login field is required.';
		} catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
			$error = 'Password field is required.';
		} catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			$error = 'Invalid username or password.';
		} catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
			$error = 'Invalid username or password.';
		} catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
			$error = 'User is not activated.';
		}
		// The following is only required if throttle is enabled
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
			$error = 'User is suspended.';
		} catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
			$error = 'User is banned.';
		}

		throw new Exception($error, 1);
	}

	static function logout( $redirect = true )
	{
		// Logs the user out
		Sentry::logout();

		if ( $redirect ) {
			return Redirect::route('home');
		}
	}

	static function has_tests()
	{
		$user = self::get_current_user();

		return DB::table('test_tests')
			->where('created_by', $user->id)
			// ->remember(15)
			->pluck('id')
			;
	}

	static function has_sessions()
	{
		$user = self::get_current_user();

		return DB::table('test_sessions')
			->where('user_id', $user->id)
			// ->remember(15)
			->pluck('id')
			;
	}

	static function json_success_response( $data, $code = 200 )
	{
		$data['success'] = true;
		return Response::json((object) $data, ($code ? $code : 200));
	}

	static function json_error_response( $data, $code = 400 )
	{
		$data['success'] = false;
		$data['error'] = true;
		return Response::json((object) $data, ($code ? $code : 400));
	}

	static function immediate_redirect( $url, $code = '301' )
	{
		switch ( $code )
		{
			case '200':
				$msg = '200 OK';
				break;

			case '301':
			default:
				$msg = '301 Moved Permanently';
				break;
		}

		header('HTTP/1.1 ' . $msg);
		header('Location: ' . $url);

		exit;
	}

	static function string_url_safe( $string )
	{
		// Remove any '-' from the string since they will be used as concatenaters
		$str = str_replace('-', ' ', $string);

		// Trim white spaces at beginning and end of alias and make lowercase
		$str = trim(strtolower($str));

		// Remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '-', $str);

		// Trim dashes at beginning and end of alias
		$str = trim($str, '-');

		return $str;
	}
}