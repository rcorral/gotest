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
		$this->test_session = $this->get('TestSession');

		// Check to see that this is even a valid session and that it is active
		if ( !$this->test_session->test_id
			|| !$this->test_session->unique_id
			|| !$this->test_session->is_active
		) {
			Tests::add_script( array( 'jquery', 'bootstrap', 'bootstrap-responsive' ) );
			parent::display( 'noexists' );
			return;
		}

		$this->test = $this->get('Test');

		if ( empty( $this->test ) ) {
			JError::raiseError( 500, 'Test does\'t exist.' );
			return false;
		}

		Tests::add_script( array( 'jquery', 'bootstrap', 'bootstrap-responsive', 'timer', 'core',
			'deparam', 'socket.io', 'click', 'templates' ) );

		Tests::addScriptDeclaration( "var api_key = '"
			. THelper::get_api_key( null, true ). "';" );

		// Set the anon_id
		if ( $this->test->anon ) {
			$uri = JFactory::getURI();
			Tests::addScriptDeclaration( "var anon_id = '" . $uri->getVar( '_' ). "';" );
		}

		parent::display( $tpl );
	}
}
