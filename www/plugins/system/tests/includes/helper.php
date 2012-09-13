<?php
/**
 * @version		$Id: ppwell.php 21766 2011-07-08 12:20:23Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Use for misc functions
 */
class TestsHelper
{
	function stripslashes_deep( $value )
	{
		if ( is_array( $value ) ) {
			$value = array_map( array( 'PPWHelper', 'stripslashes_deep' ), $value );
		} elseif ( is_object( $value ) ) {
			$vars = get_object_vars( $value );
			foreach ( $vars as $key => $data ) {
				$value->{$key} = self::stripslashes_deep( $data );
			}
		} else {
			$value = stripslashes( $value );
		}

		return $value;
	}

	function get_component_parameters()
	{
		jimport('joomla.application.component.helper');

		return JComponentHelper::getComponent( 'com_tests' );
	}

	function send_error_email( $data )
	{
		$domain = JURI::getInstance()->toString( array( 'host' ));

		return JUtility::sendMail( 'rx.corral@gmail.com', 'Error notificator',
			'rx.corral@gmail.com', '[Site Error] ' . $domain, print_r( $data, true ) );
	}
}
