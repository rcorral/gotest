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
		$isNew = ( $this->item->id == 0 );

		JToolBarHelper::title('Manage Tests');

		JToolBarHelper::apply('test_edit.apply');
		JToolBarHelper::save('test_edit.save');
		JToolBarHelper::save2new('test_edit.save2new');

		if ( $isNew ) {
			JToolBarHelper::cancel('test_edit.cancel');
		} else {
			JToolBarHelper::cancel('test_edit.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
