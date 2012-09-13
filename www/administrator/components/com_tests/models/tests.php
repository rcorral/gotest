<?php
/**
 * @package     Tests
 * @copyright   Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class TestsModelTests extends JModelList
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
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
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

		// Load the filter state.
		$search = $this->getUserStateFromRequest( $this->context
			. '.filter.search', 'filter_search' );
		$this->setState( 'filter.search', $search );

		$published = $this->getUserStateFromRequest( $this->context
			. '.filter.published', 'filter_published', 1 );
		$this->setState( 'filter.published', $published );

		// List state information.
		parent::populateState( 'a.title', 'asc' );
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
		$query = $this->_db->getQuery( true )
			->select( 'a.*' )
			->from( '#__test_tests AS a' )
			->where( 'a.`published` = ' . (int) $this->getState( 'filter.published' ) )
			;

		// Filter by search in title.
		$search = $this->getState( 'filter.search' );
		if ( !empty( $search ) ) {
			if ( stripos( $search, 'id:' ) === 0 ) {
				$query->where( 'a.id = ' . (int) substr( $search, 3 ) );
			} else {
				$search = $this->_db->Quote( '%'.$this->_db->escape($search, true).'%' );
				$query->where('(a.`title` LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')' );
			}
		}

		// Add the list ordering clause.
		$order_col  = $this->state->get( 'list.ordering', 'a.title' );
		$order_dirn = $this->state->get( 'list.direction', 'asc' );

		$query->order( $this->_db->escape( $order_col . ' ' . $order_dirn ) );

		return $query;
	}
}
