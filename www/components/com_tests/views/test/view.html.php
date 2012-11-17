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
		$model = $this->getModel();
		$this->test_session = THelper::get_test_session_id_from_url();

		if ( !$this->test_session->test_id || !$this->test_session->unique_id ) {
			Tests::add_script( array( 'jquery', 'bootstrap', 'bootstrap-responsive' ) );
			parent::display( 'noexists' );
			return;
		}

		$model->setState( 'test_session', $this->test_session );

		$this->test = $this->get('Test');

		if ( empty( $this->test ) ) {
			JError::raiseError( 500, 'Test does\'t exist.' );
			return false;
		}

		Tests::add_script( array( 'jquery', 'bootstrap', 'bootstrap-responsive', 'timer', 'core',
			'deparam', 'socket.io', 'click', 'templates' ) );

		Tests::addScriptDeclaration( "var api_key = '"
			. THelper::get_api_key( null, true ). "';" );

		parent::display( $tpl );
	}
}
