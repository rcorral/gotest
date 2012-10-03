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
	/**
	 * Gets a question template
	 */
	function get_question_type( $type, $from = 'file' )
	{
		if ( !$type ) {
			return '';
		}

		$db = JFactory::getDBO();

		$query = $db->getQuery( true )
			->select( '`id`, `title`, `html`' )
			->from( '#__test_question_types' )
			->where( '`type` = ' . $db->q( $type ) )
			;
		$row = $db->setQuery( $query )->loadObject();

		if ( 'file' == $from ) {
			$row->html = JFile::read( JPATH_ADMINISTRATOR
				. '/components/com_tests/assets/question_templates/'
				. JFile::makeSafe( $type ) . '.tmpl' );
		}

		return $row;
	}

	/**
	 * Function will get api key for a given user or create one if requested
	 */
	function get_api_key( $user_id, $autogenerate = false )
	{
		$db = JFactory::getDBO();

		if ( !$user_id ) {
			$user = JFactory::getUser();
			$user_id = $user->get('id');
		}

		$db->setQuery( "SELECT `hash`
			FROM #__api_keys
				WHERE `user_id` = " . (int) $user_id );
		$key = $db->loadResult();

		if ( !$key && $autogenerate ) {
			JLoader::register( 'APIModel', JPATH_ROOT
				. '/components/com_api/libraries/model.php' );
			JModel::addIncludePath( JPATH_ROOT . '/components/com_api/models' );
			JTable::addIncludePath( JPATH_ROOT . '/components/com_api/tables' );
			$model = JModel::getInstance( 'Key', 'ApiModel' );

			$data = array( 'id' => null, 'user_id' => $user_id, 'domain' => '', 'published' => 1 );
			if ( !$return = $model->save( $data ) ) {
				return false;
			}

			$key = $return->hash;
		}

		return $key;
	}

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
