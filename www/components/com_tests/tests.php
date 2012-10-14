<?php
/**
 * @package		Tests
 * @subpackage	com_tests
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

if ( !JFactory::getUser()->get('id') ) {
	JRequest::setVar( 'view', 'login' );
	JRequest::setVar( 'tmpl', 'component' );
	JRequest::setVar( 'tmpl2', 'compone33nt' );
}

// Execute the task.
$controller	= JController::getInstance( 'Tests' );
$controller->execute( JRequest::getCmd( 'task' ) );