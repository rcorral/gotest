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
}