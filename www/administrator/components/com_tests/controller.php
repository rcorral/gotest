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
			TestsCHelper::addSubmenu( $view );
		}

		if ( 'test' == $view ) {
			$unique_id = JRequest::getVar( 'unique_id' );
			if ( !$unique_id ) {
				// Lets generate id for this test
				$unique_id = THelper::generate_unique_test_id( JRequest::getInt( 'test_id' ) );

				if ( !$unique_id ) {
					JError::raiseError( 500, 'Error creating unique ID for test.' );
				}

				$url = JURI::getInstance();
				$url->setVar( 'unique_id', $unique_id );

				$this->setRedirect( $url->toString( array( 'query', 'fragment' ) ) );
			}
		}

		return parent::display();
	}
}
