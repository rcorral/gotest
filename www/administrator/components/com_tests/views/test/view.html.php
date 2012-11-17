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

		Tests::add_script( array( 'jquery', 'bootstrap', 'bootstrap-responsive' ) );

		if ( empty( $this->test )
			|| !THelper::is_test_session_active( $this->test->id,
				JRequest::getVar( 'unique_id' ) )
		) {
			parent::display( 'noexists' );
			return false;
		}

		Tests::add_script( array( 'timer', 'core', 'socket.io', 'click', 'templates' ) );

		Tests::addScriptDeclaration( "var api_key = '"
			. THelper::get_api_key( null, true ). "';" );

		parent::display( $tpl );
	}
}
