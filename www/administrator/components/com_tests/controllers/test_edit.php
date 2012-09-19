<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controllerform' );

/**
 * The test_edit Type Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_tests
 * @since		1.6
 */
class TestsControllerTest_Edit extends JControllerForm
{
	/**
	 * Method to save a test_edit item.
	 *
	 * @return	void
	 */
	public function save( $key = null, $urlVar = null )
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app		= JFactory::getApplication();
		$data		= JRequest::getVar('jform', array(), 'post', 'array');
		$context	= 'com_tests.edit.test_edit';
		$task		= $this->getTask();
		$recordId	= JRequest::getInt('id');

		// Get the model and attempt to validate the posted data.
		$model	= $this->getModel('Test_Edit');
		$form	= $model->getForm();
		if ( !$form ) {
			JError::raiseError( 500, $model->getError() );

			return false;
		}

		$data	= $model->validate( $form, $data );

		// Check for validation errors.
		if ( $data === false ) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ( $i = 0, $n = count( $errors ); $i < $n && $i < 3; $i++ )
			{
				if ( $errors[$i] instanceof Exception ) {
					$app->enqueueMessage( $errors[$i]->getMessage(), 'warning' );
				} else {
					$app->enqueueMessage( $errors[$i], 'warning' );
				}
			}

			// Save the data in the session.
			$app->setUserState( 'com_tests.edit.test_edit.data', $data );

			// Redirect back to the edit screen.
			$this->setRedirect(
				JRoute::_( 'index.php?option=com_tests&view=test_edit&layout=edit', false ) );

			return false;
		}

		// Attempt to save the data.
		if ( !$model->save( $data ) ) {
			// Save the data in the session.
			$app->setUserState( 'com_tests.edit.test_edit.data', $data );

			// Redirect back to the edit screen.
			$this->setMessage(
				JText::sprintf( 'JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError() ),
					'warning' );
			$this->setRedirect( JRoute::_( 'index.php?option=com_tests&view=test_edit&layout=edit',
				false ) );

			return false;
		}

		$this->setMessage( JText::_( 'COM_TESTS_TEST_SAVE_SUCCESS' ) );

		// Redirect the user and adjust session state based on the chosen task.
		switch ( $task ) {
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState( $this->context . '.id' );
				$this->holdEditId( $context, $recordId );

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_( 'index.php?option=com_tests&view=test_edit&layout=edit'
						. $this->getRedirectToItemAppend( $recordId ), false ) );
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId( $context, $recordId );
				$app->setUserState( $context . '.data', null );

				// Redirect back to the edit screen.
				$this->setRedirect( JRoute::_( 'index.php?option=com_tests&view=test_edit&layout=edit',
					false ) );
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId( $context, $recordId );
				$app->setUserState( $context . '.data', null );

				// Redirect to the list screen.
				$this->setRedirect( JRoute::_( 'index.php?option=com_tests&view=tests', false ) );
				break;
		}
	}
}
