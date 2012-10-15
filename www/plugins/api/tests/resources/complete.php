<?php
/**
 * @package	API
 * @version 1.5
 * @author 	Rafael Corral
 * @link 	http://www.corephp.com
 * @copyright Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');
jimport('joomla.filter.filteroutput');

class TestsApiResourceComplete extends ApiResource
{
	public function get()
	{
		$this->plugin->setResponse( 'here is a get request' );
	}

	public function post()
	{
		APIHelper::setSessionUser();
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

		if ( !$user->authorise('core.admin') ) {
			throw new JException( 'Not authorised.', 400 );
		}

		$test_id = JRequest::getVar( 'test_id' );
		$unique_id = JRequest::getVar( 'unique_id' );

		if ( !$test_id || !$unique_id ) {
			throw new JException( 'Invalid request', 400 );
		}

		$query = $db->getQuery( true )
			->update( '#__test_sessions' )
			->set( '`is_active` = 0' )
			->where( '`test_id` = ' . (int) $test_id )
			->where( '`unique_id` = ' . $db->q( $unique_id ) )
			->where( '`user_id` = ' . (int) $user->get('id') )
			;
		$db->setQuery( $query )->query();

		if ( $db->getErrorNum() ) {
			throw new JException( 'Error disactivating test session.', 500 );
		}

		$response = $this->getSuccessResponse( 201, 'Deactivated' );

		$this->plugin->setResponse( $response );
	}
}