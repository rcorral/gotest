<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TestsViewSessions extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display( $tpl = null )
	{
		$this->items      = $this->get( 'Items' );
		$this->pagination = $this->get( 'Pagination' );
		$this->state      = $this->get( 'State' );

		// Check for errors.
		if ( count( $errors = $this->get( 'Errors' ) ) ) {
			JError::raiseError( 500, implode( "\n", $errors ) );
			return false;
		}

		$this->addToolbar();
		parent::display( $tpl );
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$actions = TestsHelper::getActions();

		JToolBarHelper::title( JText::_( 'COM_TESTS_VIEW_SESSIONS' ) );

		if ( $actions->get( 'core.edit.state' ) ) {
			JToolBarHelper::publish( 'sessions.activate', 'COM_TESTS_ACTIVATE', true );
			JToolBarHelper::unpublish( 'sessions.deactivate', 'COM_TESTS_DEACTIVATE', true );
		}

		if ( $actions->get( 'core.delete' ) ) {
			JToolBarHelper::deleteList( '', 'sessions.delete' );
			JToolBarHelper::divider();
		}

		if ( $actions->get( 'core.admin' ) ) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_tests');
		}
	}
}
