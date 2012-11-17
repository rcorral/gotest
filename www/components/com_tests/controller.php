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
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$model = $this->getModel( 'Test', 'TestsModel' );
		$session = $model->getTestSession();
		$test = $model->getTest();

		// Lets see if we need to authenticate, check if test is not anonymous and user not logged
		// **If session id is invalid, it will get caught by the view and show an error page
		if ( $session->unique_id && $session->is_active && !$test->anon && !$user->get('id') ) {
			// Check to see if we should be triggering any authentication plugins
			if ( JRequest::getVar( 'auth' ) ) {
				JPluginHelper::importPlugin( 'authentication' );
				$app->triggerEvent( 'clickerBeginAuthentication' );
			} elseif ( JRequest::getVar( 'authenticate' ) ) {
				// Authenticate
				JPluginHelper::importPlugin( 'authentication' );
				$app->triggerEvent( 'clickerAuthenticate' );
			} else {
				JRequest::setVar( 'view', 'login' );
			}
		} elseif ( $session->unique_id && $session->is_active && $test->anon ) {
			// Let's check to see if URL has a unique_id if not create it
			$uri = JFactory::getURI();
			$unique = $uri->getVar( '_' );

			if ( !$unique || strlen( $unique ) != 8 ) {
				$unique = THelper::generate_unique_anon_id( $test->id, $session->unique_id );

				if ( !$unique ) {
					JError::raiseError( 500, 'Error generating unique id.' );
					return false;
				}

				$uri->setVar( '_', $unique );
				$app->redirect( $uri );
			}
		}

		return parent::display();
	}
}
