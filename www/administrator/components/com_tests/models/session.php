<?php
/**
 * @package     Tests
 * @copyright   Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class TestsModelSession extends JModelList
{
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	$type	The table type to instantiate
	 * @param	string	$prefix	A prefix for the table class name. Optional.
	 * @param	array	$config	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable( $type = 'Sessions', $prefix = 'TestsTable', $config = array() )
	{
		return JTable::getInstance( $type, $prefix, $config );
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState( $record )
	{
		$user = JFactory::getUser();

		if ( $user->authorise( 'core.admin' ) || $user->get('id') == $record->user_id ) {
			return true;
		}

		return false;
	}

	/**
	 * Method to change the is_active state of one or more records.
	 *
	 * @param   array    &$pks   A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function activate( &$pks, $value = 0 )
	{
		// Initialise variables.
		$user = JFactory::getUser();
		$table = $this->getTable();
		$pks = (array) $pks;

		// Access checks.
		foreach ( $pks as $i => $pk ) {
			$table->reset();

			if ( $table->load( $pk ) ) {
				if ( !$this->canEditState( $table ) ) {
					// Prune items that you can't change.
					unset( $pks[$i] );
					JError::raiseWarning( 403,
						JText::_( 'JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED' ) );
					return false;
				}
			}
		}

		$query = $this->_db->getQuery( true )
			->update( '#__test_sessions' )
			->set( 'is_active = ' . (int) $value )
			->where( 'id IN (' .implode( ',', $pks ). ')' )
			;
		$this->_db->setQuery( $query )->query();

		if ( $this->_db->getErrorNum() ) {
			JError::raiseWarning( 403, $this->_db->getErrorMsg() );
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}
}
