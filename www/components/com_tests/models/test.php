<?php
/**
 * @package     Tests
 * @copyright   Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class TestsModelTest extends JModel
{
	public function getTestSession()
	{
		if ( $test_session = $this->getState( 'test_session' ) ) {
			return $test_session;
		}

		$test_session = THelper::get_test_session_id_from_url();
		$this->setState( 'test_session', $test_session );

		return $test_session;
	}

	public function getTest()
	{
		$test_id = (int) $this->getTestSession()->test_id;

		$query = $this->_db->getQuery(true)
			->select( 't.*' )
			->from( '#__test_tests AS t' )
			->where( 't.`id` = ' . $test_id )
			;
		return $this->_db->setQuery( $query )->loadObject();
	}
}