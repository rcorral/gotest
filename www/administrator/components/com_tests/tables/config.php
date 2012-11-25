<?php
/**
 * @package		Tests
 * @subpackage	com_tests
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class TestsTableConfig extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	function __construct( &$db )
	{
		parent::__construct( '#__test_config', 'id', $db );
	}

	/**
	 * Check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		$user       =& JFactory::getUser();
		$config     =& JFactory::getConfig();
		$createdate =& JFactory::getDate();
		$createdate->setOffset( $config->getValue( 'config.offset' ) );

		$this->modified    = $createdate->toMySQL();
		$this->modified_by = $user->id;

		return true;
	}

	/**
	* Overloaded bind function
	*
	* @access public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	function bind( $array, $ignore = '' )
	{
		if ( is_array( $array['params'] ) ) {
			$registry = new JRegistry();
			$registry->loadArray( $array['params'] );
			$array['params'] = $registry->toString();
		}

		return parent::bind( $array, $ignore );
	}
}
