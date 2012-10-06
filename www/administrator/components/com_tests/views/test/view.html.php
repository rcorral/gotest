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

		Tests::add_script( array( 'jquery', 'bootstrap', 'bootstrap-responsive', 'core',
			'socket.io', 'click', 'templates' ) );

		Tests::addScriptDeclaration( "var api_key = '"
			. TestsHelper::get_api_key( null, true ). "';" );

		parent::display( $tpl );
	}
}
