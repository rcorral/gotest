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

	static function get_question_templates()
	{
		$types = DB::table('test_question_types')
			->get()
			;

		$templates = array();
		foreach ( $types as $type ) {
			$templates[$type->type] = $type;
		}

		return $templates;
	}

	/**
	 * Gets a question template
	 */
	static function get_question_type( $type )
	{
		if ( !$type )
			return '';

		$row = DB::table('test_question_types')
			->select('id', 'title', 'html')
			->where('type', $type)
			->first()
			;

		return $row;
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
		try {
			// Get the current active/logged in user
			return Sentry::getUser();
		} catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			// User wasn't found, should only happen if the user was deleted
			// when they were already logged in or had a "remember me" cookie set
			// and they were deleted.
			Helper::logout(false);
			App::abort(401, 'You are not authorized.');
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

	static function json_success_response( $data, $code = 200 )
	{
		$data['success'] = true;
		return Response::json((object) $data, $code);
	}

	static function json_error_response( $data, $code = 400 )
	{
		$data['success'] = false;
		$data['error'] = true;
		return Response::json((object) $data, $code);
	}
}