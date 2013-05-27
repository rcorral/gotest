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

	static function modal_html( $contents, $title = '', $footer = '' )
	{
		
	}
}