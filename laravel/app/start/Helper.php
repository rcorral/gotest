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

	static function modal_html( $contents, $title = '', $footer = '' )
	{
		
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
}