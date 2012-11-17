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
class THelper
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

		if ( !$user_id ) {
			return '';
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

	/**
	 * Function will generate a new unique test session id for the given test_id and user_id
	 */
	function generate_unique_test_id( $test_id, $user_id = null )
	{
		if ( !$user_id ) {
			$user_id = JFactory::getUser()->get('id');
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery( true );
		$test_id = (int) $test_id;
		$user_id = (int) $user_id;
		$date = date( 'Y-m-d H:i:s' );
		$unique_id = '';

		if ( !$test_id ) {
			return false;
		}

		$counter = 0;
		while ( !$unique_id ) {
			if ( $counter ) {
				$_unique = md5( $date . $test_id . $user_id . $counter );
			} else {
				$_unique = md5( $date . $test_id . $user_id );
			}

			$query->clear()
				->select( 'ts.`id`' )
				->from( '#__test_sessions AS ts' )
				->where( 'ts.`unique_id` = ' . $db->q( $_unique ) )
				;
			if ( !$db->setQuery( $query )->loadResult() ) {
				$query->clear()
					->insert( '#__test_sessions' )
					->columns( '`test_id`, `user_id`, `unique_id`, `is_active`, `date`' )
					->values( "{$test_id}, {$user_id}, " .$db->q( $_unique ). ", 1, '{$date}'" )
					;
				$db->setQuery( $query )->query();

				if ( $db->getErrorNum() ) {
					return false;
				}

				$unique_id = $_unique;
			}

			$counter++;
		}

		return $unique_id;
	}

	/**
	 * Gets the test_id and the unique identifier for this testing session from the URL
	 */
	function get_test_session_id_from_url()
	{
		static $return;

		if ( $return ) {
			return $return;
		}

		$path = str_replace( JURI::root( true ), '', $_SERVER['REQUEST_URI'] );
		$return = (object) array(
			'test_id' => '',
			'unique_id' => '',
			'is_active' => 0
			);

		// Match
		preg_match( '/^\/([0-9]+)\/([0-9a-zA-Z]{6})\/?/', $path, $matches );

		if ( isset( $matches[1] ) && $matches[1] ) {
			$return->test_id = (int) $matches[1];
		}

		if ( $return->test_id && isset( $matches[2] ) && $matches[2] ) {
			$db = JFactory::getDBO();

			$query = $db->getQuery( true )
				->select( 'ts.`unique_id`, ts.`is_active`' )
				->from( '#__test_sessions AS ts' )
				->where( 'ts.`is_active` = 1' )
				->where( 'ts.`test_id` = ' . (int) $return->test_id )
				->where( 'ts.`unique_id` LIKE \'' .$db->escape( $matches[2] ). '%\'' )
				;
			$result = $db->setQuery( $query, 0, 1 )->loadObject();
			$return->unique_id = @$result->unique_id;
			$return->is_active = @$result->is_active;
		}

		return $return;
	}

	/**
	 * See if test is still active
	 */
	function is_test_session_active( $test_id, $unique_id )
	{
		$db = JFactory::getDBO();

		$query = $db->getQuery( true )
			->select( '`is_active`' )
			->from( '#__test_sessions AS ts' )
			->where( '`test_id` = ' . (int) $test_id )
			->where( '`unique_id` = ' . $db->q( $unique_id ) )
			;
		return $db->setQuery( $query )->loadResult();
	}

	/**
	 * Function will generate a new unique id for an anonymous test session
	 */
	function generate_unique_anon_id( $test_id, $session_id )
	{
		if ( !$test_id || !$session_id ) {
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery( true );
		$test_id = (int) $test_id;
		$date = date( 'Y-m-d H:i:s' );
		$unique_id = '';

		$counter = 0;
		while ( !$unique_id ) {
			if ( $counter ) {
				$_unique = md5( $date . $test_id . $session_id . $counter );
			} else {
				$_unique = md5( $date . $test_id . $session_id );
			}

			// Make smaller and prefix, one in a billion chances someone will have the same id
			$_unique = substr( $_unique, 0, 6 ) . date( 's' );

			$query->clear()
				->select( 'ta.`anon_user_id`' )
				->from( '#__test_answers AS ta' )
				->leftjoin( '#__test_sessions AS ts ON ts.`id` = ta.`session_id`' )
				->where( 'ts.`test_id` = ' . $db->q( $test_id ) )
				->where( 'ts.`unique_id` = ' . $db->q( $session_id ) )
				->where( 'ta.`anon_user_id` = ' . $db->q( $_unique ) )
				;
			if ( !$db->setQuery( $query )->loadResult() ) {
				$unique_id = $_unique;
			}

			$counter++;
		}

		return $unique_id;
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
