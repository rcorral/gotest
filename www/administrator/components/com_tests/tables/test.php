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

	/**
	 * Overloaded check function
	 *
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		// Check for valid title
		if ( trim( $this->title ) == '' ) {
			$this->setError( JText::_( 'COM_TESTS_WARNING_PROVIDE_VALID_NAME' ) );
			return false;
		}

		if ( empty( $this->alias ) ) {
			$this->alias = $this->title . JFactory::getDate()->format( 'Y-m-d-H-i-s' );
		}

		$this->alias = JApplication::stringURLSafe( $this->alias );

		// Check for valid category
		if ( trim( $this->catid ) == '' ) {
			$this->setError( JText::_( 'COM_TESTS_WARNING_CATEGORY' ) );
			return false;
		}

		return true;
	}

	/**
	 * Stores a test
	 *
	 * @param	boolean	True to update fields even if they are null.
	 * @return	boolean	True on success, false on failure.
	 * @since	1.6
	 */
	public function store( $updateNulls = false )
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ( $this->id ) {
			// Existing item
			$this->modified    = $date->toSql();
			$this->modified_by = $user->get( 'id' );
		} else {
			if ( !intval( $this->created ) ) {
				$this->created = $date->toSql();
			}

			if ( empty( $this->created_by ) ) {
				$this->created_by = $user->get( 'id' );
			}
		}

		// Verify that the alias is unique
		$table = JTable::getInstance( 'Test', 'TestsTable' );
		if ( $table->load( array( 'alias' => $this->alias, 'catid' => $this->catid ) )
			&& ( $table->id != $this->id || $this->id == 0 )
		) {
			$this->setError( JText::_( 'COM_TESTS_ERROR_UNIQUE_ALIAS' ) );
			return false;
		}

		// Attempt to store the data.
		return parent::store( $updateNulls );
	}

}
