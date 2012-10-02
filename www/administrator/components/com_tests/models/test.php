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
	public function getTest()
	{
		$test_id = JRequest::getInt( 'test_id' );

		$query = $this->_db->getQuery(true)
			->select( 't.*' )
			->from( '#__test_tests AS t' )
			->where( 't.`id` = ' . (int) $test_id )
			;
		return $this->_db->setQuery( $query )->loadObject();
	}
}
