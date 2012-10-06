<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TestsViewTest extends JView
{
	/**
	 * Display the view
	 */
	public function display( $tpl = null )
	{
		$this->test = $this->get('Test');

		if ( empty( $this->test ) ) {
			JError::raiseError( 500, 'Test does\'t exist.' );
			return false;
		}

		$this->document = JFactory::getDocument();
		$this->document->addStyleSheet( JURI::root()
			. 'components/com_tests/assets/css/template.css' );
		Tests::add_script(
			array( 'jquery', 'bootstrap', 'core', 'socket.io', 'click', 'templates' ) );

		parent::display( $tpl );
	}
}
