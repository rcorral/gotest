<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TestsViewTests extends JView
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

		// Levels filter.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1', JText::_('J1'));
		$options[]	= JHtml::_('select.option', '2', JText::_('J2'));
		$options[]	= JHtml::_('select.option', '3', JText::_('J3'));
		$options[]	= JHtml::_('select.option', '4', JText::_('J4'));
		$options[]	= JHtml::_('select.option', '5', JText::_('J5'));
		$options[]	= JHtml::_('select.option', '6', JText::_('J6'));
		$options[]	= JHtml::_('select.option', '7', JText::_('J7'));
		$options[]	= JHtml::_('select.option', '8', JText::_('J8'));
		$options[]	= JHtml::_('select.option', '9', JText::_('J9'));
		$options[]	= JHtml::_('select.option', '10', JText::_('J10'));

		$this->f_levels = $options;

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

		JToolBarHelper::title( JText::_( 'COM_TESTS_VIEW_TESTS' ) );

		if ( $actions->get( 'core.create' ) ) {
			JToolBarHelper::addNew( 'test_edit.add' );
		}

		if ( $actions->get( 'core.edit' ) || $actions->get( 'core.edit.own' ) ) {
			JToolBarHelper::editList( 'test_edit.edit' );
		}

		if ( $actions->get( 'core.edit.state' ) ) {
			JToolBarHelper::divider();
			JToolBarHelper::publish( 'tests.publish', 'JTOOLBAR_PUBLISH', true );
			JToolBarHelper::unpublish( 'tests.unpublish', 'JTOOLBAR_UNPUBLISH', true );
			JToolBarHelper::divider();
			JToolBarHelper::archiveList( 'tests.archive' );
		}

		if ( $this->state->get( 'filter.published' ) == -2 && $actions->get( 'core.delete' ) ) {
			JToolBarHelper::deleteList( '', 'tests.delete', 'JTOOLBAR_EMPTY_TRASH' );
			JToolBarHelper::divider();
		} elseif ( $actions->get( 'core.edit.state' ) ) {
			JToolBarHelper::trash( 'tests.trash' );
			JToolBarHelper::divider();
		}

		if ( $actions->get( 'core.admin' ) ) {
			JToolBarHelper::preferences('com_tests');
		}
	}
}
