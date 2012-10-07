<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Tests master display controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class TestsController extends JController
{
	public function display()
	{
		$view = JRequest::getCmd('view', 'tests');

		// set default view if not set
		JRequest::setVar('view', $view);

		if ( JFactory::getApplication()->isAdmin() ) {
			// Set the submenu
			// TestsCHelper::addSubmenu($view);
		}

		return parent::display();
	}
}
