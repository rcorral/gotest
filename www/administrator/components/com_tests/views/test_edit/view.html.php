<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TestsViewTest_Edit extends JView
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display( $tpl = null )
	{
		$this->form      = $this->get('Form');
		$this->item      = $this->get('Item');
		$this->state     = $this->get('State');
		$this->questions = $this->get('Questions');
		$this->templates = $this->get('Templates');

		// Check for errors.
		if ( count( $errors = $this->get('Errors') ) ) {
			JError::raiseError( 500, implode( "\n", $errors ) );
			return false;
		}

		Tests::add_script( array( 'jquery', 'colorbox', 'core' ) );

		parent::display( $tpl );
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$user = JFactory::getUser();
		$actions = TestsHelper::getActions();
		$is_new = ( $this->item->id == 0 );
		$can_edit = $is_new || $this->item->created_by == $user->get( 'id' )
			|| $actions->get( 'core.admin' );

		JToolBarHelper::title('Manage Tests');

		if ( $can_edit ) {
			JToolBarHelper::apply('test_edit.apply');
			JToolBarHelper::save('test_edit.save');
			JToolBarHelper::save2new('test_edit.save2new');
		} else {
			JError::raise( E_NOTICE, 200, JText::_( 'COM_TEST_TEST_VIEW_NO_EDIT' ) );
		}

		if ( $is_new ) {
			JToolBarHelper::cancel('test_edit.cancel');
		} else {
			JToolBarHelper::cancel('test_edit.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
