<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport( 'joomla.database.table' );

class TestsTableTest extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	function __construct( &$db )
	{
		parent::__construct( '#__test_tests', 'id', $db );
	}
}
