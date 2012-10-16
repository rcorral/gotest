<?php
/**
 * @package     Tests
 * @copyright   Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class TestsModelSessions extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if ( empty( $config['filter_fields'] ) ) {
			$config['filter_fields'] = array(
				'active', 'ts.active',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState( $ordering = null, $direction = null )
	{
		// Initialise variables.
		$app = JFactory::getApplication( 'administrator' );

		$active = $this->getUserStateFromRequest( $this->context
			. '.filter.active', 'active', null );
		$this->setState( 'filter.active', $active );

		// List state information.
		parent::populateState( 'ts.date', 'desc' );
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		$user = JFactory::getUser();

		$query = $this->_db->getQuery( true )
			->select( 'ts.`id`, ts.`date`, ts.`is_active`' )
			->from( '#__test_sessions AS ts' )
			->leftjoin( '#__test_tests AS t ON t.`id` = ts.`test_id`' )
			->where( 'ts.`user_id` = ' . $user->get('id') )
			;

		// Filter by active
		$active = $this->getState( 'filter.active' );
		if ( null != $active ) {
			$query->where( 'ts.`active` = ' . (int) $active );
		}

		// Add the list ordering clause.
		$order_col  = $this->state->get( 'list.ordering', 'ts.title' );
		$order_dirn = $this->state->get( 'list.direction', 'asc' );

		$query->order( $this->_db->escape( $order_col . ' ' . $order_dirn ) );

		return $query;
	}
}
