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
				'is_active', 'a.is_active',
				'title', 'a.title',
				'id', 'a.id',
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
		parent::populateState( 'a.date', 'desc' );
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
			->select( 'ts.`id`, ts.`date`, ts.`is_active`, ts.`unique_id`,
				t.`id` AS `test_id`, t.`title`, t.`sub_title`, t.`anon`,
				ta.`user_id`, ta.`anon_user_id`' )
			->from( '#__test_sessions AS ts' )
			->leftjoin( '#__test_tests AS t ON t.`id` = ts.`test_id`' )
			->leftjoin( '#__test_answers AS ta ON ta.`session_id` = ts.`id`' )
			->where( 'ts.`user_id` = ' . $user->get('id') )
			->group( 'ts.`id`, IF( t.`anon` = 1, ta.`anon_user_id`, ta.`user_id` )' )
			;

		// Filter by active
		$active = $this->getState( 'filter.active' );
		if ( null != $active ) {
			$query->where( 'ts.`active` = ' . (int) $active );
		}

		$_query = $this->_db->getQuery( true )
			->select( 'a.`id`, a.`date`, a.`is_active`, a.`unique_id`,
				a.`test_id`, a.`title`, a.`sub_title`,
				IF( a.`anon` = 1, COUNT( a.`anon_user_id` ), COUNT( a.`user_id` ) ) AS `count`
				' )
			->from( '(' . $query . ') AS a' )
			->group( 'a.`id`' )
			;

		// Add the list ordering clause.
		$order_col  = $this->state->get( 'list.ordering', 'ts.title' );
		$order_dirn = $this->state->get( 'list.direction', 'asc' );

		$_query->order( $this->_db->escape( $order_col . ' ' . $order_dirn ) );

		return $_query;
	}
}
