<?php
/**
 * @package		Tests
 * @subpackage	com_tests
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Access check.
if ( !JFactory::getUser()->authorise( 'core.manage', 'com_users' ) ) {
	return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

JLoader::register( 'TestsHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/tests.php' );

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JController::getInstance( 'Tests' );
$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();
