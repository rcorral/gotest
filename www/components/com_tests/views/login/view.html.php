<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TestsViewLogin extends JView
{
	/**
	 * Display the view
	 */
	public function display( $tpl = null )
	{
		Tests::add_script( array( 'jquery', 'bootstrap', 'bootstrap-responsive' ) );

		parent::display( $tpl );
	}
}
